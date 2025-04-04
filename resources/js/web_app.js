require('./bootstrap');
import { Viewer, utils } from '@photo-sphere-viewer/core';
import { EquirectangularTilesAdapter } from '@photo-sphere-viewer/equirectangular-tiles-adapter';
import { LensflarePlugin } from 'photo-sphere-viewer-lensflare-plugin';
import { AutorotatePlugin } from '@photo-sphere-viewer/autorotate-plugin';
import { MarkersPlugin } from '@photo-sphere-viewer/markers-plugin';

document.addEventListener('DOMContentLoaded', () => {
    let viewer;
    // const locations = JSON.parse(document.getElementById('app').getAttribute('data-location'));

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


    //create value animation
    const animatedValues = {
        pitch: { start: -Math.PI / 2, end: 0 },
        yaw: { start: Math.PI / 2, end: 0 },
        zoom: { start: 0, end: 50 },
        maxFov: { start: 130, end: 90 },
        fisheye: { start: 2, end: 0 },
    };
    let isInit = true;


















    const createViewer = (location) => {
        if (!location) {
            alert('Hệ thống đang lỗi');
            return;
        }

        const locationIdElements = document.getElementsByClassName('location-id');
        for (const element of locationIdElements) {
            element.value = location.id || 0;
        }

        const plugins = [];
        plugins.push([MarkersPlugin, { markers: [] }], [LensflarePlugin, { lensflares: [] }], [AutorotatePlugin, {
            autostartDelay: null,
            autostartOnIdle: false,
            autorotatePitch: 0,
            autorotateSpeed: '0.3rpm',
        }]);

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

        updateNextLocationButton(location);

        if (!viewer) {
            // console.log("New Viewer");
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
                defaultPitch: animatedValues.pitch.start,
                defaultYaw: animatedValues.yaw.start,
                defaultZoomLvl: animatedValues.zoom.start,
                maxFov: animatedValues.maxFov.start,
                fisheye: animatedValues.fisheye.start,
                mousemove: false,
                mousewheel: false,
                plugins: plugins,
                touchmoveTwoFingers: false,
                mousewheelCtrlKey: false,
            });

            const autorotate = viewer.getPlugin(AutorotatePlugin);

            //start animation
            function intro(pitch, yaw) {
                // console.log(autorotate)
                isInit = false;
                autorotate.stop();

                new utils.Animation({
                    properties: {
                        ...animatedValues,
                        pitch: { start: animatedValues.pitch.start, end: pitch },
                        yaw: { start: animatedValues.yaw.start, end: yaw },
                    },
                    duration: 2500,
                    easing: 'inOutQuad',
                    onTick: (properties) => {
                        viewer.setOptions({
                            fisheye: properties.fisheye,
                            maxFov: properties.maxFov,
                        });
                        viewer.rotate({ yaw: properties.yaw, pitch: properties.pitch });
                        viewer.zoom(properties.zoom);
                    },
                }).then(() => {
                    autorotate.start();
                    viewer.setOptions({
                        mousemove: true,
                        mousewheel: true,
                    });
                });
            }

            viewer.addEventListener('ready', () => {
                document.getElementById('play-audio-btn').addEventListener('click', function () {
                    const bgElement = document.getElementById('start-display');
                    const sidebar = document.querySelector('.main-side-bar');
                    let icon_sidebar = document.getElementById('sidebar-icon');
                    let icon_volume = document.getElementById('volume-icon');
                    let icon_autorote = document.getElementById('autorotate-icon');


                    //description
                    updateDescription(location)

                    //Toggle Voice Reader
                    updateVoiceReader(location)
                    document.getElementById("voice-toggle-btn").addEventListener('click', function () {
                        let icon_toggle_voice_btn = document.getElementById("voice-toggle-icon-btn");

                        if (document.getElementById("voice-reader-audio").paused) {
                            document.getElementById("voice-reader-audio").play();
                            icon_toggle_voice_btn.classList.replace('bi-volume-mute', 'bi-volume-up-fill');
                        } else {
                            document.getElementById("voice-reader-audio").pause();
                            icon_toggle_voice_btn.classList.replace('bi-volume-up-fill', 'bi-volume-mute');
                        }
                    })



                    //Toggle side bar
                    document.getElementById('toggle-sidebar-btn').addEventListener('click', function () {
                        if (sidebar.classList.contains('show')) {
                            sidebar.classList.remove('show');
                            sidebar.classList.add('hide');
                            icon_sidebar.classList.remove('bi-list');
                            icon_sidebar.classList.add('bi-unindent');
                        } else {
                            sidebar.classList.remove('hide');
                            sidebar.classList.add('show');
                            icon_sidebar.classList.remove('bi-unindent');
                            icon_sidebar.classList.add('bi-list');
                        }
                    });

                    bgElement.classList.add('fade-out');

                    setTimeout(function () {
                        bgElement.style.display = 'none';
                    }, 1000);


                    setTimeout(function () {
                        if (isInit) {
                            intro(animatedValues.yaw.end, animatedValues.pitch.end);

                            sidebar.classList.add('show');

                            setTimeout(() => {
                                updateMarkers(viewer, location.hotlinks, location.hotlinks_special);
                            }, 2000);
                        }
                    }, 750);


                    //Toggle Rotate
                    document.getElementById("toggle-rotate-btn").addEventListener('click', function () {
                        autorotate.toggle()

                        if (icon_autorote.classList.contains("bi-play")) {
                            icon_autorote.classList.remove("bi-play");
                            icon_autorote.classList.add("bi-pause");
                        } else {
                            icon_autorote.classList.add("bi-play");
                            icon_autorote.classList.remove("bi-pause");
                        }
                    });


                    //Toggle Volume
                    updateBackgroundMusic(location);
                    document.getElementById('toggle-volume-bg').addEventListener('click', function () {
                        let audio = document.getElementById('background-audio');

                        if (audio.paused) {
                            audio.play();
                            icon_volume.classList.replace('bi-volume-mute', 'bi-volume-up-fill');
                        } else {
                            audio.pause();
                            icon_volume.classList.replace('bi-volume-up-fill', 'bi-volume-mute');
                        }
                    });








                    //Screenshot
                    function screenShot() {
                        viewer.addEventListener('render', () => {
                            const link = document.createElement('a');
                            link.download = 'screenshot.png';
                            link.href = viewer.renderer.renderer.domElement.toDataURL();
                            link.click();
                        }, { once: true });
                        viewer.needsUpdate();
                    };

                    document.getElementById('screenshot-btn').addEventListener('click', screenShot);
                });


                if (location.sun) {
                    updateSun(viewer, location.sun);
                }


                const videoOverlay = document.getElementById('videoOverlay');
                const youtubeIframe = document.getElementById('youtubeIframe');

                viewer.getPlugin(MarkersPlugin).addEventListener('select-marker', ({ marker }) => {
                    if (!isNaN(marker.data) && !isNaN(parseFloat(marker.data))) {
                        const selectedLocationById = locations.find(location => location.id === Number(marker.data));
                        if (selectedLocationById) {
                            window.updateOrder(selectedLocationById.id);
                        }
                    } else if (marker['config']['data_content_type'] == 'video') {
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

                    console.log(`Clicked at yaw: ${yaw}, pitch: ${pitch}`);
                });
            });
        } else {
            // console.log("Update Viewer");
            updateBackgroundMusic(location);
            updateDescription(location);
            updateVoiceReader(location);






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



        function updateNextLocationButton(location) {
            let nextLocationButton = document.getElementById("next-location-action");
            if (location.next_location) {
                let next_location = location.next_location;
                let nextLocationName = document.getElementById("next-location-name");

                nextLocationName.textContent = next_location.name;

                nextLocationButton.setAttribute("onclick", `updateOrder(${next_location.id})`);
                nextLocationButton.style.display = "flex";
            } else {
                nextLocationButton.style.display = "none";
            }
        }
    };



    const updateVoiceReader = (location) => {
        let voice_lang;
        const audioElement = document.getElementById("voice-reader-audio");
        const sourceElement = audioElement.querySelector("source");
        const btn_voice_reader = document.getElementById("voice-toggle-btn");
        const icon_voice_reader = document.getElementById("voice-toggle-icon-btn");

        if (language == "vi") {
            voice_lang = "voice";
        } else {
            voice_lang = "voice_en";
        }

        //voice reader
        let voice_lang_folder = location[voice_lang];
        let fullPath = "/storage/" + voice_lang_folder;

        if (voice_lang_folder != null && voice_lang_folder !== '') {
            btn_voice_reader.disabled = false;
            if (icon_voice_reader.classList.contains('bi-volume-mute')) {
                icon_voice_reader.classList.replace('bi-volume-mute', 'bi-volume-up-fill');
            }
            btn_voice_reader.classList.remove("disabled-color");
            sourceElement.src = fullPath;
            audioElement.load();
            audioElement.play();
        } else {
            btn_voice_reader.disabled = true;
            if (icon_voice_reader.classList.contains('bi-volume-up-fill')) {
                icon_voice_reader.classList.replace('bi-volume-up-fill', 'bi-volume-mute');
            }
            if (!btn_voice_reader.classList.contains("disabled-color")) {
                btn_voice_reader.classList.add("disabled-color");
            }
            audioElement.pause();
            audioElement.currentTime = 0;
        }
    };

    const updateDescription = (location) => {
        let descriptionHolder = document.getElementById("location-description-voice");
        descriptionHolder.innerHTML = location.description;
    };

    const updateBackgroundMusic = (location) => {
        const audioElement = document.getElementById('background-audio');
        const sourceElement = audioElement.querySelector("source");
        const originalSource = audioElement.getAttribute("data-audio-url");
        const icon_volume = document.getElementById("volume-icon");

        if (location.category.background_music != null && location.category.background_music !== '') {
            let audioUrl = '/storage/' + location.category.background_music;

            const currentPath = sourceElement.src.split(window.location.origin)[1];

            // console.log(currentPath);
            // console.log(audioUrl);

            if (currentPath !== audioUrl) {
                sourceElement.src = audioUrl;

                // console.log("new song");
                audioElement.load();
                audioElement.play();
                icon_volume.classList.replace('bi-volume-mute', 'bi-volume-up-fill');
            }
        } else {
            sourceElement.src = originalSource;

            // console.log("orginal song");
            audioElement.load();
            audioElement.play();
            icon_volume.classList.replace('bi-volume-mute', 'bi-volume-up-fill');
        }
        // if (initAudio) {
        //     initAudio = false;
        //     audioElement.play();
        // }
    }







    const updateMarkers = (viewerInstance, hotlinks, hotlinksSpecial) => {
        // console.log(hotlinksSpecial);
        const markersPluginUpdate = viewerInstance.getPlugin(MarkersPlugin);


        if (!markersPluginUpdate) {
            console.error("MarkersPlugin is not initialized.");
            return;
        }

        const markers = hotlinks.map((hotlink) => {
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
});
