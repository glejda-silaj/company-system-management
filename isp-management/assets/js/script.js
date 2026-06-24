console.log("ISP Management System Loaded");

function toggleSidebar() {
   
    document.querySelector('.sidebar').classList.toggle('active');
    document.querySelector('.sidebar-overlay').classList.toggle('active');
    document.querySelector('.menu-toggle').classList.toggle('hide');
}