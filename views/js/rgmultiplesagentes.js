function toggleWhatsappList() {
    const list = document.getElementById('whatsapp-agentes-list');
    if (list.style.display === 'none' || list.style.display === '') {
        list.style.display = 'block';
    } else {
        list.style.display = 'none';
    }
}
