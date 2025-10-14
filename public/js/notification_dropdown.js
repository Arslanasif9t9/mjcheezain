document.addEventListener("DOMContentLoaded", function () {
    const bellBtn = document.getElementById("notification-button");
    const dropdown = document.getElementById("notification-dropdown");
    const dot = bellBtn.querySelector("span");
    
    let hasSeen = false;
    
    bellBtn.addEventListener("click", function () {
        dropdown.classList.remove("hidden");
        console.log(dropdown.classList);

        if (!hasSeen) {
            fetch('notification_dropdown.php')
                .then(res => res.json())
                .then(data => {
                    const container = dropdown.querySelector(".divide-y");
                    container.innerHTML = ""; // Clear previous items
                    
                    data.forEach(item => {
                        const element = document.createElement("a");
                        element.href = "#";
                        element.className = "block px-4 py-3 hover:bg-gray-50";
                        element.innerHTML = `
                        <div class="text-sm font-medium text-gray-800">${item.title}</div>
                        <div class="text-xs text-gray-500 mt-1 truncate">${item.value}</div>
                        `;
                        container.appendChild(element);
                    });
                    console.log(container);

                    dot.classList.add("hidden"); // Hide red dot
                    // hasSeen = true;
                });
        }
    });
});
