const Modal = document.getElementById('modal');
const openModal = document.getElementById('openModal');
const closeModal = document.getElementById('closeModal');
const closeModal_edit = document.getElementById('closeModal-edit');
const openModal_edit = document.getElementById('openModal-edit');

//function for save products
document.getElementById('productForm').addEventListener("submit", function (event) {
    event.preventDefault();
    let formData = new FormData(this);
    fetch('./server/save_product.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json()).then(data => {
        if (data.success) {
            alert(data.messageSuccess);
            document.getElementById('productForm').reset();
            document.getElementById('modal').classList.add('hidden');
            location.reload(true); //refresh the page
        } else {
            alert(data.messageError);
        }
    }).catch(error => {
        console.error('Error:', error);
    });
})

//function for edit products
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('productForm-edit');
    if (form) {
        form.addEventListener("submit", function (event) {
            if (confirm("¿Estás seguro de actualizar este producto?")) {
                event.preventDefault();
                const formData_update = new FormData();
                formData_update.append('productCode-edit', document.getElementById('productCode-edit').value);
                formData_update.append('productName-edit', document.getElementById('productName-edit').value);
                formData_update.append('productDescription-edit', document.getElementById('productDescription-edit').value);
                formData_update.append('productCategory-edit', document.getElementById('productCategory-edit').value);
                formData_update.append('productPrice-edit', document.getElementById('productPrice-edit').value);
                formData_update.append('productStock-edit', document.getElementById('productStock-edit').value);

                const imageFile = document.getElementById('productImage-edit').files[0];
                if (imageFile) {
                    formData_update.append('productImage-edit', imageFile);
                }
                fetch('./server/update_product.php', {
                    method: 'POST',
                    body: formData_update
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.messageSuccess);
                            form.reset();
                            document.getElementById('modal-edit').classList.add('hidden');
                            location.reload(true); //refresh the page
                        } else {
                            alert(data.messageError);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });
    }
});


//function for delete products
function deleteProduct(code) {
    if (confirm('¿Estás seguro de eliminar este producto?')) {
        fetch('./server/delete_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ code: code })
        }).then(response => response.json()).then(data => {
            if (data.success) {
                alert(data.messageSuccess);
                location.reload(true); //refresh the page
            } else {
                alert(data.messageError);
            }
        }).catch(error => {
            console.error('Error:', error);
        })
    }
}

//function for edit products
function loadProduct(code) {
    fetch('./server/get_product.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ code: code })
    }).then(response => response.json()).then(data => {
        if (data.success) {
            document.getElementById('productForm-edit').reset(); //reset the form-edit
            //fill the form with the data of the product
            document.getElementById('productCode-edit').value = data.product.code;
            document.getElementById('productName-edit').value = data.product.name;
            document.getElementById('productDescription-edit').value = data.product.description;
            document.getElementById('productCategory-edit').value = data.product.category;
            document.getElementById('productPrice-edit').value = data.product.price;
            document.getElementById('productStock-edit').value = data.product.stock;
            document.getElementById('productImage-edit').value = "";
            document.getElementById('modal-edit').classList.remove('hidden');
        } else {
            alert(data.messageError);
        }
    }).catch(error => { console.log('Error:', error) });
}

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

//functions for modal edit
openModal_edit.addEventListener('click', () => {
    document.getElementById('modal-edit').classList.remove('hidden');
});

closeModal_edit.addEventListener('click', () => {
    document.getElementById('modal-edit').classList.add('hidden');
    document.getElementById('productForm-edit').reset();
})

window.addEventListener('click', (event) => {
    if (event.target === document.getElementById('modal-edit')) {
        document.getElementById('modal-edit').classList.add('hidden');
    }
})