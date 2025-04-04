require('./bootstrap');
import { Viewer } from '@photo-sphere-viewer/core';
import { EquirectangularTilesAdapter } from '@photo-sphere-viewer/equirectangular-tiles-adapter';
import { LensflarePlugin } from 'photo-sphere-viewer-lensflare-plugin';
import { MarkersPlugin } from '@photo-sphere-viewer/markers-plugin';

document.addEventListener('DOMContentLoaded', () => {
    let viewer;
    // const locations = JSON.parse(document.getElementById('app').getAttribute('data-location'));
    // console.log(locations)

    let locations;

    async function getLocations() {
        try {
            const response = await fetch('/api/get-location-infomation');
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            locations = await response.json();
            // console.log("Fetched locations:", locations);
            let initialLocation = locations[0];
            if (locationIdCurrent) {
                const foundIndex = locations.findIndex(location => location.id === parseInt(locationIdCurrent));
                const foundId = locations.find(location => location.id === parseInt(locationIdCurrent));

                if (foundIndex !== -1) {
                    window.updateOrder(foundId.id);
                }
            } else {
                createViewer(initialLocation);
            }
        } catch (error) {
            console.error("Error fetching locations:", error);
        }
    }

    // Call the function to fetch the locations
    getLocations();





    const radToDeg = (rad) => rad * (180 / Math.PI);


    const createViewer = (location) => {
        // console.log("RAN CreateViewer");
        if (!location) {
            document.getElementById('viewer').innerHTML = "<h4>Không có điểm ảnh nào</h4>";
            document.getElementById('get-location-name-app').innerText = "Không có điểm ảnh nào";
            document.getElementById('get-location-name-modal').innerText = "Không có điểm ảnh nào";
            return;
        }

        document.getElementById('get-location-name-app').innerText = location.name || "Không tên";
        document.getElementById('get-location-name-modal').innerText = location.name || "Không tên";
        const locationIdElements = document.getElementsByClassName('location-id');
        for (const element of locationIdElements) {
            element.value = location.id || 0;
        }

        const plugins = [];
        plugins.push([MarkersPlugin, { markers: [] }], [LensflarePlugin, { lensflares: [] }]);

        const basePath = location.paronama.image.substring(0, location.paronama.image.lastIndexOf('/'));
        const baseUrl = `${window.baseUrl}storage/${basePath}/low.webp`;
        const tileUrl = (col, row) => `${window.baseUrl}storage/${basePath}/tiles/tile_${col}_${row}.webp`;

        let defaultYaw;
        let defaultPitch;

        if (location.eyes != null) {
            let eyesData = JSON.parse(location.eyes);
            defaultYaw = `${radToDeg(eyesData.yaw).toFixed(2)}deg`;
            defaultPitch = `${radToDeg(eyesData.pitch).toFixed(2)}deg`;

            // console.log(defaultYaw, defaultPitch);
        } else {
            defaultYaw = '359deg';
            defaultPitch = '-45deg';
        }


        if (!viewer) {
            viewer = new Viewer({
                container: document.querySelector('#viewer'),
                navbar: false,
                adapter: EquirectangularTilesAdapter,
                panorama: {
                    width: 12000,
                    cols: 16,
                    rows: 8,
                    baseUrl: baseUrl,
                    tileUrl: tileUrl,
                },
                defaultYaw: defaultYaw,
                defaultPitch: defaultPitch,
                plugins: plugins,
                touchmoveTwoFingers: true,
                mousewheelCtrlKey: true,
            });

            viewer.addEventListener('ready', () => {
                updateMarkers(viewer, location.hotlinks, location.hotlinks_special);
                if (location.sun) {
                    updateSun(viewer, location.sun);
                }

                const videoOverlay = document.getElementById('videoOverlay');
                const youtubeIframe = document.getElementById('youtubeIframe');

                viewer.getPlugin(MarkersPlugin).addEventListener('select-marker', ({ marker }) => {
                    if (!isNaN(marker.data) && !isNaN(parseFloat(marker.data))) {
                        // console.log("location click")
                        const selectedLocationById = locations.find(location => location.id === Number(marker.data));
                        if (selectedLocationById) {
                            window.updateOrder(selectedLocationById.id);
                        }
                    } else if (marker['config']['data_content_type'] == 'video') {
                        // console.log("video click")
                        videoOverlay.style.display = 'flex';
                        videoOverlay.addEventListener('click', (event) => {
                            if (event.target === videoOverlay) {
                                videoOverlay.style.display = 'none';
                                youtubeIframe.src = '';
                            }
                        });
                        youtubeIframe.src = 'https://www.youtube.com/embed/' + marker.data;
                    }
                });

                // const radToDeg = (rad) => rad * (180 / Math.PI);

                // Lắng nghe sự kiện click để chọn tọa độ
                viewer.addEventListener('dblclick', ({ data }) => {
                    const yaw = radToDeg(data.yaw).toFixed(2);
                    const pitch = radToDeg(data.pitch).toFixed(2);

                    document.querySelectorAll('.yaw-value').forEach(el => el.value = yaw);
                    document.querySelectorAll('.pitch-value').forEach(el => el.value = pitch);

                    // console.log(`Clicked at yaw: ${yaw}, pitch: ${pitch}`);

                    const modal = new bootstrap.Modal(document.getElementById('addHotSpot'));
                    modal.show();
                });
            });
        } else {
            // console.log("RELOAD WORLD: " + location.sun);
            const markersPlugin = viewer.getPlugin(MarkersPlugin);
            if (markersPlugin) {
                markersPlugin.clearMarkers();
            }

            const lensflarePlugin = viewer.getPlugin(LensflarePlugin);
            if (lensflarePlugin) {
                lensflarePlugin.clearLensflares();
            }





            viewer.animate({
                yaw: defaultYaw,
                pitch: defaultPitch,
                speed: '15rpm',
            }).then(() => {
                viewer.setPanorama({
                    width: 12000,
                    cols: 16,
                    rows: 8,
                    baseUrl: baseUrl,
                    tileUrl: tileUrl,
                }).then(() => {
                    updateMarkers(viewer, location.hotlinks, location.hotlinks_special);
                    if (location.sun) {
                        updateSun(viewer, location.sun);
                    }
                }).catch((error) => {
                    console.error('Failed to animate viewer:', error);
                });
            }).catch((error) => {
                console.error('Failed to update panorama:', error);
            });
        }
    };

    const updateMarkers = (viewerInstance, hotlinks, hotlinksSpecial) => {
        // console.log("RAN UpdateMarkers");
        const markersPluginUpdate = viewerInstance.getPlugin(MarkersPlugin);
        const hotlinksList = document.getElementById("hotlinks_list");
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const deleteHotlinkRoute = hotlinksList.dataset.deleteRoute;
        const deleteHotlinkRouteSpecial = hotlinksList.dataset.deleteRouteSpecial;


        if (!markersPluginUpdate) {
            console.error("MarkersPlugin is not initialized.");
            return;
        }

        while (hotlinksList.firstChild) {
            hotlinksList.removeChild(hotlinksList.firstChild);
        }

        if (hotlinks.length === 0 && hotlinksSpecial.length === 0) {
            const noHotlinkMessage = document.createElement("span");
            noHotlinkMessage.classList.add("text-muted", "fw-bold");
            noHotlinkMessage.textContent = "Chưa có hot spot";
            hotlinksList.appendChild(noHotlinkMessage);
        }

        const markers = hotlinks.map((hotlink) => {
            const listItem = document.createElement("li");
            listItem.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center", "p-1");
            listItem.innerHTML = `<i class="bi bi-link-45deg"></i>
        <span class="fw-bold">${hotlink.next_location.name}</span>
        <form method="POST" action="${deleteHotlinkRoute}" class="mb-0">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="hotlink_id" value="${hotlink.id}">
            <input type="hidden" name="current_location_id" value="${hotlink.location_id}">
            <button type="submit" class="ms-1 btn btn-danger rounded btn-sm"><i class="bi bi-trash"></i></button>
        </form>
    `;
            hotlinksList.appendChild(listItem);


            const nextLocation = hotlink.next_location;
            const basePath = nextLocation.paronama.image.substring(
                0,
                nextLocation.paronama.image.lastIndexOf("/")
            );

            const image = `${window.baseUrl}storage/${basePath}/fisheye.webp`;

            if (hotlink.type == 1) {
                return {
                    id: `hotlink_${hotlink.id}`,
                    position: { yaw: `${hotlink.yaw}deg`, pitch: `${hotlink.pitch}deg` },
                    anchor: "center",
                    html: `<div class="psv-content">
                    <div class="bubble-effect">
                      <div class="img-container">
                        <img
                          src="${image}"
                          class="hot_link_hover img-fluid fade-image"
                          draggable="false"
                        />
                      </div>
                      <div class="hotlink_name mt-2">
                        <strong>${nextLocation.name}</strong>
                      </div>
                    </div>
                  </div>
                `,
                    size: { width: 56, height: 56 },
                    scale: { zoom: [0.5, 1] },
                    className: "psv-marker--custom",
                    data: `${hotlink.next_location.id}`,
                };
            } else if (hotlink.type == 2) {
                return {
                    id: `hotlink_${hotlink.id}`,
                    position: { yaw: `${hotlink.yaw}deg`, pitch: `${hotlink.pitch}deg` },
                    anchor: "center",
                    image: "/assets/images/arrow.png",
                    size: { width: 112, height: 112 },
                    tooltip: `${nextLocation.name}`,
                    scale: { zoom: [0.5, 1] },
                    data: `${hotlink.next_location.id}`,
                }
            } else if (hotlink.type == 3) {
                return {
                    id: `hotlink_${hotlink.id}`,
                    position: { yaw: `${hotlink.yaw}deg`, pitch: `${hotlink.pitch}deg` },
                    anchor: "center",
                    image: "/assets/images/flycam.png",
                    size: { width: 112, height: 112 },
                    tooltip: `${nextLocation.name}`,
                    scale: { zoom: [0.5, 1] },
                    data: `${hotlink.next_location.id}`,
                }
            } else if (hotlink.type == 5) {
                return {
                    id: `hotlink_${hotlink.id}`,
                    position: { yaw: `${hotlink.yaw}deg`, pitch: `${hotlink.pitch}deg` },
                    anchor: "center",
                    html: `<div class="location-sign">
                            ${nextLocation.name}
                          <div class="stick">
                            <div class="dot"></div>
                          </div>
                        </div>`,
                    size: { width: 112, height: 112 },
                    scale: { zoom: [0.5, 1] },
                    data: `${hotlink.next_location.id}`,
                }
            }
        });
        const markersSpecial = hotlinksSpecial.map((hotlink) => {
            // console.log(hotlink)
            const listItem = document.createElement("li");
            let name = null;
            if (hotlink.type === 6) {
                name = 'Video';
            } else {
                name = 'Thông tin'
            }
            listItem.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center", "p-1");
            listItem.innerHTML = `<i class="bi bi-info-circle"></i>
        <span class="fw-bold">${name}_${hotlink.id}</span>
        <form method="POST" action="${deleteHotlinkRouteSpecial}" class="mb-0">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="hotlink_id" value="${hotlink.id}">
            <input type="hidden" name="current_location_id" value="${hotlink.location_id}">
            <button type="submit" class="ms-1 btn btn-danger rounded btn-sm"><i class="bi bi-trash"></i></button>
        </form>
    `;
            hotlinksList.appendChild(listItem);


            if (hotlink.type == 6) {
                return {
                    id: `hotlinkSpecial_${hotlink.id}`,
                    position: { yaw: `${hotlink.yaw}deg`, pitch: `${hotlink.pitch}deg` },
                    anchor: "center",
                    html: `<button class="btn hotspot_special_btn" id="playInfoContentButton">
                             <i class="bi bi-collection-play"></i>
                           </button>`,
                    size: { width: 56, height: 56 },
                    scale: { zoom: [0.5, 1] },
                    data: `${hotlink.video_link}`,
                    data_content_type: 'video',
                };
            } else if (hotlink.type == 7) {
                return {
                    id: `hotlinkSpecial_${hotlink.id}`,
                    position: { yaw: `${hotlink.yaw}deg`, pitch: `${hotlink.pitch}deg` },
                    anchor: "center",
                    html: `<button class="btn hotspot_special_btn">
                             <i class="bi bi-info-lg"></i>
                           </button>`,
                    content: hotlink.info_content,
                    size: { width: 56, height: 56 },
                    scale: { zoom: [0.5, 1] },
                    data: 'info',
                }
            }
        });

        const allMarkers = [...markers, ...markersSpecial].filter(Boolean);

        markersPluginUpdate.setMarkers(allMarkers);
    };

    const updateSun = (viewerInstance, sun) => {
        // console.log("LOADED SUN");
        const lensflarePluginUpdate = viewerInstance.getPlugin(LensflarePlugin);
        const hotlinksList = document.getElementById("hotlinks_list");
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const deleteHotlinkRouteSpecial = hotlinksList.dataset.deleteRouteSpecial;

        const listItem = document.createElement("li");
        listItem.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center", "p-1");
        listItem.innerHTML = `
        <i class="bi bi-sun-fill"></i>
        <span class="fw-bold">Mặt_Trời_${sun.id}</span>
        <form method="POST" action="${deleteHotlinkRouteSpecial}" class="mb-0">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="sun_id" value="${sun.id}">
            <button type="submit" class="ms-1 btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
        </form>
    `;
        hotlinksList.appendChild(listItem);


        if (!lensflarePluginUpdate) {
            console.error("LensflarePlugin is not initialized.");
            return;
        }

        const sun_lens = [{ id: 'sun_' + sun.id, position: { yaw: sun.yaw + 'deg', pitch: sun.pitch + 'deg' }, type: 0 }];
        lensflarePluginUpdate.setLensflares(sun_lens);
    };

    window.updateOrder = (id) => {
        // console.log("RAN updateOrder");
        const selectedLocation = locations.find(location => location.id === id);
        createViewer(selectedLocation);

        document.querySelectorAll('.location-item').forEach(item => {
            item.classList.remove('active');
        });

        const selectedElement = document.getElementById(`location-${id}`);
        if (selectedElement) {
            selectedElement.classList.add('active');
        }
    };

    document.querySelector('#saveEyeBtn').addEventListener('click', function (event) {
        event.preventDefault();
        const position = viewer.getPosition();
        const yaw = position['yaw'];
        const pitch = position['pitch'];

        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch("/admin/location/eye/set", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                yaw: yaw,
                pitch: pitch,
                location_id: document.querySelector('.location-id').value
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) { // Kiểm tra `success` thay vì `status`
                    alert("✅ " + data.message); // Hiển thị thông báo thành công
                } else {
                    alert("⚠️ Lỗi: " + (data.error || "Đã xảy ra lỗi!"));
                }
            })
            .catch(error => {
                alert("❌ Lỗi kết nối đến server! Vui lòng thử lại.");
                console.error("Error:", error);
            });
    });
});
