document.addEventListener("DOMContentLoaded", () => {
    const nameInput = document.getElementById("name");
    const slugInput = document.getElementById("slug");

    // Transliteration map for Cyrillic and special Latin chars
    const transliterationMap = {
        // Cyrillic (BG, SR, MK, UK, RU-like)
        а: "a", б: "b", в: "v", г: "g", д: "d", е: "e", ж: "zh", з: "z",
        и: "i", й: "j", к: "k", л: "l", м: "m", н: "n", о: "o", п: "p",
        р: "r", с: "s", т: "t", у: "u", ф: "f", х: "h", ц: "c", ч: "c",
        ш: "s", щ: "sht", ы: "y", ъ: "a", э: "e", ю: "yu", я: "ya",
        ђ: "dj", љ: "lj", њ: "nj", џ: "dz", ґ: "g", є: "e", ї: "i", і: "i",

        А: "A", Б: "B", В: "V", Г: "G", Д: "D", Е: "E", Ж: "Zh", З: "Z",
        И: "I", Й: "J", К: "K", Л: "L", М: "M", Н: "N", О: "O", П: "P",
        Р: "R", С: "S", Т: "T", У: "U", Ф: "F", Х: "H", Ц: "C", Ч: "C",
        Ш: "S", Щ: "Sht", Ы: "Y", Ъ: "A", Э: "E", Ю: "Yu", Я: "Ya",
        Ђ: "Dj", Љ: "Lj", Њ: "Nj", Џ: "Dz", Ґ: "G", Є: "E", Ї: "I", І: "I",

        // Romanian
        ă: "a", â: "a", î: "i", ș: "s", ţ: "t", Ț: "T", Ş: "S", Â: "A", Î: "I", Ă: "A", Ș: "S",

        // Croatian / Slovenian / Bosnian
        č: "c", ć: "c", đ: "d", š: "s", ž: "z",
        Č: "C", Ć: "C", Đ: "D", Š: "S", Ž: "Z",

        // Hungarian
        ő: "o", ű: "u", Ő: "O", Ű: "U",

        // Baltic (Latvian / Lithuanian / Estonian extras)
        ā: "a", ē: "e", ģ: "g", ī: "i", ķ: "k", ļ: "l", ņ: "n", ū: "u",
        Ā: "A", Ē: "E", Ģ: "G", Ī: "I", Ķ: "K", Ļ: "L", Ņ: "N", Ū: "U",
    };

    const transliterate = (text) => {
        return text.split("").map(char => transliterationMap[char] || char).join("");
    };

    const generateSlug = () => {
        // 🛑 Do nothing if slug already has a value
        if (slugInput.value.trim() !== "") {
            return;
        }

        const name = nameInput.value;
        const transliterated = transliterate(name);

        let slug = transliterated.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "") // remove diacritics
            .replace(/[^a-z0-9]+/g, "-") // replace non-alphanum with "-"
            .replace(/-+/g, "-") // collapse multiple "-"
            .replace(/^-|-$/g, ""); // trim "-"

        if (!slug) slug = "n-a";

        slugInput.value = slug;
    };

    // Generate slug on page load if needed
    if (nameInput.value && !slugInput.value) {
        generateSlug();
    }

    // Update slug from name only if slug is empty
    nameInput.addEventListener("input", generateSlug);
});