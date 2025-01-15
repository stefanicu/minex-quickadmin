document.addEventListener("DOMContentLoaded", () => {
    const nameInput = document.getElementById("name");
    const slugInput = document.getElementById("slug");

    // Function to generate slug from name
    const generateSlug = () => {
        const slug = nameInput.value
            .toLowerCase()                        // Convert to lowercase
            .replace(/[^a-z0-9\s-]/g, '')         // Remove invalid characters
            .trim()                               // Remove leading/trailing spaces
            .replace(/\s+/g, '-');                // Replace spaces with hyphens

        slugInput.value = slug;
    };
    // Generate slug on page load if name has data but slug is empty
    if (nameInput.value && !slugInput.value) {
        generateSlug();
    }

    // Update slug as user types in the name field
    nameInput.addEventListener("input", generateSlug);
});