const fileInput = document.getElementById("images") as HTMLInputElement | null;
const feedback = document.getElementById("image-count-feedback") as HTMLParagraphElement | null;
const uploadRow = document.querySelector(".file-upload") as HTMLElement | null;

if (fileInput && feedback && uploadRow) {
    const currentImages = Number(uploadRow.dataset.currentImages??"0");
    const minImages = Number(uploadRow.dataset.minImages??"2");

    const updateFeedback = () => {
        const selectedImages = fileInput.files?.length ?? 0;
        const totalImages = currentImages + selectedImages;
        const remainingImages = Math.max(0, (minImages-totalImages));

        feedback.innerHTML = 'Selected: <strong>${selectedImages}</strong> image(s). ' + 'Total after upload: <strong>${totalImages}</strong> / ${minImages}.' + (remainingImages > 0? ' You need ${remainingImages} more image(s).':'Minimum requirement met.');
    };

    fileInput.addEventListener("change", updateFeedback);
    updateFeedback();
}