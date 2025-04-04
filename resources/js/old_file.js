// document.addEventListener('DOMContentLoaded', () => {
//   let hot_link_name = "Demo Position";
//   let hot_link_image = "storage/360/trong-den/fisheye.webp";


//   const viewer = new Viewer({
//     container: document.querySelector('#viewer'),
//     navbar: false,
//     adapter: EquirectangularTilesAdapter,
//     panorama: {
//       width: 12000,
//       cols: 16,
//       rows: 8,
//       baseUrl: `${window.baseUrl}/storage/360/trong-den/low.webp`, // Ảnh gốc
//       tileUrl: (col, row) => {
//         return `${window.baseUrl}/storage/360/trong-den/tiles/tile_${col}_${row}.webp`;
//       },
//     },
//     defaultYaw: '359deg',
//     defaultPitch: '-45deg',



//     plugins: [
//       // [AutorotatePlugin, {
//       //   autorotatePitch: '-1deg',
//       //   autorotateSpeed: '1rpm',
//       // }],
//       [LensflarePlugin, {
//         lensflares: [
//           {
//             id: 'sun',
//             position: { yaw: '272deg', pitch: '30deg' },
//             type: 0,
//           }
//         ]
//       }],
//       [MarkersPlugin, {
//         markers: [
//           {
//             id: 'destination_1',
//             position: { yaw: '0deg', pitch: '-85deg' },
//             anchor: 'center',
//             html: `<div class="psv-marker--custom d-flex flex-column align-items-center justify-content-center">
//   <div class="psv-content">
//     <div class="img-container">
//       <img
//         src="${window.baseUrl + hot_link_image}"
//         alt="${hot_link_name}"
//         class="hot_link_hover img-fluid"
//         draggable="false"
//       />
//     </div>
//     <div class="hotlink_name mt-2">
//       <strong>${hot_link_name}</strong>
//     </div>
//   </div>
// </div>
// `,
//             size: { width: 56, height: 56 },
//             scale: {
//               zoom: [0.5, 1]
//             },
//             className: "psv-marker--custom",
//           },
//         ],
//       }],
//     ],
//     touchmoveTwoFingers: true,
//     mousewheelCtrlKey: true,
//   });

//   viewer.on('ready', () => {
//     viewer.setOptions({
//       navbar: false,
//     });
//   });


//   const radToDeg = (rad) => rad * (180 / Math.PI);

//   viewer.addEventListener('click', ({ data }) => {
//     console.log(`${data.rightclick ? 'right ' : ''}clicked at yaw: ${radToDeg(data.yaw)} pitch: ${radToDeg(data.pitch)}`);
//   });
// });

















{/* <div class="psv-content">
                  <div class="bubble-effect">
                    <div class="img-container" style="--hover-image: url(${image})">
                      <img 
                        src="${image_pre}" 
                        alt="${nextLocation.name}" 
                        class="hot_link_hover img-fluid fade-image" 
                        draggable="false"
                      />
                    </div>
                    <div class="hotlink_name mt-2">
                      <strong>${nextLocation.name}</strong>
                    </div>
                  </div>
                </div> */}