document.addEventListener("DOMContentLoaded", () => {
    const nameInput = document.getElementById("name");
    const slugInput = document.getElementById("slug");

    // Transliteration map for Cyrillic to Latin
    const transliterationMap = {
        а: "a", б: "b", в: "v", г: "g", д: "d", е: "e", ж: "zh", з: "z",
        и: "i", й: "y", к: "k", л: "l", м: "m", н: "n", о: "o", п: "p",
        р: "r", с: "s", т: "t", у: "u", ф: "f", х: "h", ц: "ts", ч: "ch",
        ш: "sh", щ: "sht", ы: "y", ъ: "a", э: "e", ю: "yu", я: "ya",
        А: "A", Б: "B", В: "V", Г: "G", Д: "D", Е: "E", Ж: "Zh", З: "Z",
        И: "I", Й: "Y", К: "K", Л: "L", М: "M", Н: "N", О: "O", П: "P",
        Р: "R", С: "S", Т: "T", У: "U", Ф: "F", Х: "H", Ц: "Ts", Ч: "Ch",
        Ш: "Sh", Щ: "Sht", Ы: "Y", Ъ: "A", Э: "E", Ю: "Yu", Я: "Ya"
    };

    // Function to transliterate Cyrillic to Latin
    const transliterate = (text) => {
        return text.split('').map(char => transliterationMap[char] || char).join('');
    };

    // Function to generate slug from name
    const generateSlug = () => {
        const name = nameInput.value;
        const transliterated = transliterate(name); // Transliterate first
        const slug = transliterated.toLowerCase()                        // Convert to lowercase
            .normalize("NFD")                     // Normalize to decompose accents
            .replace(/[\u0300-\u036f]/g, '')      // Remove diacritical marks
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