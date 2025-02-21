const Modal = document.getElementById('modal');
const openModal = document.getElementById('openModal');
const closeModal = document.getElementById('closeModal');

//function for requests and responses
document.getElementById('productForm').addEventListener("submit", function(event){
    event.preventDefault();
    let formData = new FormData(this);
    fetch('./server/save_product.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json()).then(data => {
        if(data.success){
            alert(data.success);
            document.getElementById('productForm').reset();
            document.getElementById('modal').classList.add('hidden');
        }else{
            alert(data.error);
        }
    }).catch(error => {
        console.error('Error:', error);
    }); 
})

//functions for modal
openModal.addEventListener('click', () => {
    Modal.classList.remove('hidden');
});

closeModal.addEventListener('click', () => {
    Modal.classList.add('hidden');
});

window.addEventListener('click', (event) => {
    if (event.target === Modal) {
        Modal.classList.add('hidden');
    }
});