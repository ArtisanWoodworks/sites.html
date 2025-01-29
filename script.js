// Array to store the images
var images = [];
var currentIndex = 0;

// Function to open the image in a modal
function openImage(imgElement) {
    var modal = document.getElementById("imageModal");
    var modalImg = document.getElementById("modalImage");
    modal.style.display = "flex";
    modalImg.src = imgElement.src;

    // Store the images in the array for navigation
    images = document.querySelectorAll('.gallery-item');
    currentIndex = Array.from(images).indexOf(imgElement);
}

// Function to close the image modal
function closeImage() {
    var modal = document.getElementById("imageModal");
    modal.style.display = "none";
}

// Function to change images with arrows
function changeImage(event, direction) {
    event.stopPropagation();
    currentIndex += direction;

    // If we're at the start or end of the image array, loop
    if (currentIndex >= images.length) currentIndex = 0;
    if (currentIndex < 0) currentIndex = images.length - 1;
    var modalImg = document.getElementById("modalImage");
    modalImg.src = images[currentIndex].src;
}

// Event listener for keyboard navigation
document.addEventListener('keydown', function(event) {
    var modal = document.getElementById("imageModal");
    if (modal.style.display === "flex") {
        if (event.key === "ArrowLeft") {
            changeImage(event, -1);  // Left arrow key to go to the previous image
        } else if (event.key === "ArrowRight") {
            changeImage(event, 1);   // Right arrow key to go to the next image
        } else if (event.key === "Escape") {
            closeImage();            // Escape key to close the modal
        }
    }
});

// Form validation (optional)
document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var message = document.getElementById('message').value;
    if (name && email && message) {
        alert("Votre message a été envoyé avec succès !");
        // Here you can implement the functionality to actually send the form data
        // For now, we'll just clear the form
        document.getElementById('contactForm').reset();
    } else {
        alert("Veuillez remplir tous les champs.");
    }
});
document.getElementById("contactForm").addEventListener("submit", function(event){
    event.preventDefault(); // منع إعادة تحميل الصفحة

    let formData = new FormData(this);

    fetch("send_mail.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        let msgElement = document.getElementById("responseMessage");
        msgElement.style.display = "block";
        msgElement.textContent = data; 
        if (data.includes("succès")) {
            msgElement.style.color = "green";
            document.getElementById("contactForm").reset();
        } else {
            msgElement.style.color = "red";
        }
    })
    .catch(error => console.error("Erreur:", error));
});