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

// Wait for the DOM to fully load


