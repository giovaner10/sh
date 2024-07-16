function initCustomTabs(tabContainerSelector, tabContentSelector, defaultTabSelector) {
    const tabs = document.querySelectorAll(`${tabContainerSelector} .nav-link`);
    const tabContents = document.querySelectorAll(tabContentSelector);

    tabs.forEach(tab => {
        tab.addEventListener("click", function (e) {
            e.preventDefault();
            const target = this.getAttribute("href");

            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.remove("active-pane");
                content.style.display = "none";
            });

            // Remove active class from all tabs
            tabs.forEach(tab => {
                tab.classList.remove("active-tab");
            });

            // Show the targeted tab content
            document.querySelector(target).classList.add("active-pane");
            document.querySelector(target).style.display = "block";

            // Add active class to clicked tab
            this.classList.add("active-tab");
        });
    });

    // Reset to default tab on modal show if defaultTabSelector is provided
    if (defaultTabSelector) {
        document.querySelector(defaultTabSelector).click();
    }
}


