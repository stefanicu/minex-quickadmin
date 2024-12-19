$(document).ready(() => {
    let url = location.href.replace(/\/$/, "");
    const tabs = $('#myTab a[data-toggle="tab"]'); // Select all tabs
    const firstTab = tabs.first(); // First tab
    const defaultTab = $('#myTab a[href="#tab-alte"]'); // Default tab for invalid hashes

    if (location.hash) {
        // Extract the hash from the URL
        const hash = url.split("#")[1];
        const targetTab = $('#myTab a[href="#' + hash + '"]');

        if (targetTab.length) {
            // If the hash matches an existing tab, show that tab
            targetTab.tab("show");
        } else {
            // If the hash is invalid, show the default tab
            defaultTab.tab("show");
            updateUrl(defaultTab.attr("href"));
        }
    } else {
        // No hash: Show the first tab
        firstTab.tab("show");
        updateUrl(firstTab.attr("href"));
    }

    // Handle tab clicks
    $('a[data-toggle="tab"]').on("click", function () {
        const hash = $(this).attr("href");
        updateUrl(hash);
    });

    // Function to update the URL
    function updateUrl(hash) {
        const newUrl = url.split("#")[0] + hash + "/";
        history.replaceState(null, null, newUrl);
    }
});