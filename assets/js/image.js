function processImageSquare(inputFile, callback, targetSize = 250) {
  const reader = new FileReader();
  reader.onload = function (event) {
    const img = new Image();
    img.onload = function () {
      const canvas = document.createElement("canvas");
      const ctx = canvas.getContext("2d");
      const size = Math.min(img.width, img.height);
      canvas.width = size;
      canvas.height = size;
      const x = (img.width - size) / 2;
      const y = (img.height - size) / 2;
      //dibujar la imagen con altura o anchura normal
      ctx.drawImage(img, x, y, size, size, 0, 0, size, size);

      // Crear un canvas temporal para el reescalado
      const tempCanvas = document.createElement("canvas");
      const tempCtx = tempCanvas.getContext("2d");
      tempCanvas.width = targetSize;
      tempCanvas.height = targetSize;
      // Convertir la imagen tamaño normal definido
      tempCtx.drawImage(canvas, 0, 0, size, size, 0, 0, targetSize, targetSize);

      tempCanvas.toBlob(
        (blob) => {
          if (blob) {
            const newProfilePhotoBlob = new File([blob], "profile-photo.webp", {
              type: "image/webp",
            });
            callback(null, {
              dataURL: tempCanvas.toDataURL("image/webp"),
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

function processImageScale(inputFile, callback, targetSize = 150) {
  const reader = new FileReader();
  reader.onload = function (event) {
    const img = new Image();
    img.onload = function () {
      const canvas = document.createElement("canvas");
      const ctx = canvas.getContext("2d");

      let width = img.width;
      let height = img.height;

      // Calcular las dimensiones escaladas manteniendo la proporción
      if (width > height) {
        if (width > targetSize) {
          height = Math.round((height *= targetSize / width));
          width = targetSize;
        }
      } else {
        if (height > targetSize) {
          width = Math.round((width *= targetSize / height));
          height = targetSize;
        }
      }

      canvas.width = width;
      canvas.height = height;
      ctx.drawImage(img, 0, 0, width, height);

      canvas.toBlob(
        (blob) => {
          if (blob) {
            const scaledImageBlob = new File([blob], "scaled-image.webp", {
              type: "image/webp",
            });
            callback(null, {
              dataURL: canvas.toDataURL("image/webp"),
              blob: scaledImageBlob,
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
