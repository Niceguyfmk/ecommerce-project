const sidebarToggle = document.querySelector("#sidebar-toggle");
sidebarToggle.addEventListener("click",function(){
    document.querySelector("#sidebar").classList.toggle("collapsed");
});

document.querySelector(".theme-toggle").addEventListener("click",() => {
    toggleLocalStorage();
    toggleRootClass();
});

function toggleRootClass(){
    const current = document.documentElement.getAttribute('data-bs-theme');
    const inverted = current == 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-bs-theme',inverted);
}

function toggleLocalStorage(){
    if(isLight()){
        localStorage.removeItem("light");
    }else{
        localStorage.setItem("light","set");
    }
}

function isLight(){
    return localStorage.getItem("light");
}

if(isLight()){
    toggleRootClass();
}

function initializeEditRoleModal() {
    // Get the modal element
    var editRoleModal = document.getElementById('editRoleModal');
    
    // Initialize the Bootstrap modal
    var modal = new bootstrap.Modal(editRoleModal);

    // Add event listener for when the modal is shown
    editRoleModal.addEventListener('show.bs.modal', function (event) {
        // Get the button that triggered the modal
        var button = event.relatedTarget;
        
        // Extract data attributes from the button
        var userId = button.getAttribute('data-user-id');
        var currentRoleId = button.getAttribute('data-current-role-id');
        var userEmail = button.getAttribute('data-user-email');

        // Populate the modal fields with the extracted data
        document.getElementById('userId').value = userId;
        document.getElementById('userEmail').value = userEmail;
        document.getElementById('role').value = currentRoleId;
    });

    // Optionally, you can trigger the modal programmatically:
    // modal.show();  // To open the modal manually (if required)
}

//cloning fields fo metavalues fields and attribute fields
function setupCloning(options) {
    const {
        containerSelector,
        addButtonSelector,
        removeButtonSelector,
        rowClass,
        inputPattern,
    } = options;

    const container = document.querySelector(containerSelector);
    const addButton = document.querySelector(addButtonSelector);
    const removeButton = document.querySelector(removeButtonSelector);

    let rowIndex = container.querySelectorAll(`.${rowClass}`).length;

    // Add new row
    addButton.addEventListener("click", () => {
        rowIndex++;
        const firstRow = container.querySelector(`.${rowClass}`);
        const newRow = firstRow.cloneNode(true);

        // Update the heading
        const heading = newRow.querySelector(".attribute-heading");
        if (heading) {
            heading.textContent = `Attribute ${rowIndex}`;
        }

        // Clear inputs and update names
        const inputs = newRow.querySelectorAll("input, select, textarea");
        inputs.forEach((input) => {
            const name = input.getAttribute("name");
            if (name) {
                input.setAttribute(
                    "name",
                    name.replace(inputPattern, `[${rowIndex}]`)
                );
            }
            if (input.tagName.toLowerCase() !== "select") {
                input.value = ""; // Clear value
            }
        });

        // Append new row
        container.appendChild(newRow);

        // Enable remove button
        if (removeButton) {
            removeButton.disabled = container.children.length <= 1;
        }
    });

    // Remove last row
    if (removeButton) {
        removeButton.addEventListener("click", () => {
            if (container.children.length > 1) {
                container.removeChild(container.lastElementChild);
                rowIndex--;
            }

            // Disable remove button when only one row remains
            removeButton.disabled = container.children.length <= 1;
        });
    }
}

//delete metavalues or attributes dynamically
function initializeDeleteButtons() {
    // Select all delete buttons
    const deleteButtons = document.querySelectorAll('.delete-meta');

    // Add event listeners to each delete button
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const metaId = this.dataset.metaId; // Retrieve the meta_id from the button's dataset

            if (confirm('Are you sure you want to delete this meta value?')) {
                // Send an AJAX request to delete the row
                fetch(`/product/deleteMeta/${metaId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    },
                })
                    .then(response => {
                        if (response.ok) {
                            // Remove the row from the DOM
                            const rowToDelete = document.getElementById(`meta-row-${metaId}`);
                            if (rowToDelete) {
                                rowToDelete.remove();
                            }
                        } else {
                            alert('Failed to delete the meta value. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            }
        });
    });
}






