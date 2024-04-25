function processImageSquare(inputFile, canvas, callback) {
  const reader = new FileReader();
  reader.onload = function (event) {
    const img = new Image();
    img.onload = function () {
      const ctx = canvas.getContext("2d");
      const size = Math.min(img.width, img.height);
      canvas.width = size;
      canvas.height = size;
      const x = (img.width - size) / 2;
      const y = (img.height - size) / 2;
      ctx.drawImage(img, x, y, size, size, 0, 0, size, size);

      canvas.toBlob(
        (blob) => {
          if (blob) {
            const newProfilePhotoBlob = new File(
              [blob],
              "profile-photo.webp",
              {
                type: "image/webp",
              }
            );
            callback(null, {
              dataURL: canvas.toDataURL("image/webp"),
              blob: newProfilePhotoBlob,
            });
          } else {
            callback(new Error("Error processing image"));
          }
        },
        "image/webp",
        1
      );
    };
    img.src = event.target.result;
  };
  reader.readAsDataURL(inputFile);
}
