let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

const carritoIcono = document.querySelector('.fa-shopping-cart');

function actualizarContador() {
    const total = carrito.reduce((sum, item) => sum + item.cantidad, 0);

    let badge = document.getElementById('contador-carrito');
    if (!badge) {
        badge = document.createElement('span');
        badge.id = 'contador-carrito';
        badge.className = 'badge bg-warning text-dark';
        badge.style.position = 'absolute';
        badge.style.top = '10px';
        badge.style.right = '10px';
        badge.style.fontSize = '0.7rem';
        badge.style.padding = '5px';
        carritoIcono.parentElement.style.position = 'relative';
        carritoIcono.parentElement.appendChild(badge);
    }
    badge.textContent = total;
}

function guardarCarrito() {
    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarContador();
}

function actualizarCarritoEnServidor() {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];

    if (carrito.length > 0) {
        fetch('pago.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ carrito: carrito })  
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                console.log('Carrito enviado exitosamente');
            } else {
                console.log('Hubo un problema al enviar el carrito');
            }
        })
        .catch(error => {
            console.error('Error al enviar el carrito:', error);
        });
    }
}

function renderizarCarrito() {
    const contenedor = document.getElementById('carrito-contenido');
    contenedor.innerHTML = ''; 

    if (carrito.length === 0) {
        contenedor.innerHTML = '<p class="text-center">No hay productos en el carrito.</p>';
        return;
    }

    carrito.forEach((item, index) => {
        const div = document.createElement('div');
        div.className = 'd-flex justify-content-between align-items-center mb-2 border-bottom pb-2';
        div.innerHTML = `
            <div>
                <strong>${item.titulo}</strong><br>
                $${item.precio.toFixed(2)} x ${item.cantidad}
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-danger" onclick="eliminarUno(${index})">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        `;
        contenedor.appendChild(div);
    });

    const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);

    const totalDiv = document.createElement('div');
    totalDiv.className = 'mt-3 text-end';
    totalDiv.innerHTML = `<h5>Total: $${total.toFixed(2)}</h5>`;
    contenedor.appendChild(totalDiv);

    const vaciarBtn = document.createElement('button');
    vaciarBtn.className = 'btn btn-danger w-100 mt-3';
    vaciarBtn.textContent = 'Vaciar Carrito';
    vaciarBtn.onclick = vaciarCarrito;
    contenedor.appendChild(vaciarBtn);
}

function agregarAlCarrito(producto) {
    const index = carrito.findIndex(p => p.id === producto.id && p.tipo === producto.tipo);
    if (index >= 0) {
        carrito[index].cantidad += 1;
    } else {
        carrito.push({ ...producto, cantidad: 1 });
    }
    guardarCarrito();
    actualizarContador();  

    Swal.fire({
        position: 'top',  
        icon: 'success',
        title: '¡Se agregó al carrito!',
        showConfirmButton: false,
        timer: 1000,
        timerProgressBar: true,
        toast: true
    });
    carritoIcono.classList.add('vibrar');
    setTimeout(() => {
        carritoIcono.classList.remove('vibrar');
    }, 300);      
}

function eliminarUno(index) {
    carrito[index].cantidad -= 1;
    if (carrito[index].cantidad <= 0) {
        carrito.splice(index, 1);
    }
    guardarCarrito();
    renderizarCarrito();
    actualizarCarritoEnServidor();  
}

function vaciarCarrito() {
    carrito = [];
    guardarCarrito();
    renderizarCarrito();
}

document.querySelectorAll('.agregar-carrito').forEach(boton => {
    boton.addEventListener('click', () => {
        const producto = {
            id: boton.getAttribute('data-id'),
            tipo: boton.getAttribute('data-tipo'),
            titulo: boton.getAttribute('data-titulo'),
            precio: parseFloat(boton.getAttribute('data-precio'))
        };
        agregarAlCarrito(producto);
        actualizarCarritoEnServidor(); 
    });
});

document.getElementById('btn-pagar').addEventListener('click', function () {
    if (carrito.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Carrito vacío',
            text: 'No tienes productos en tu carrito.',
            confirmButtonText: 'Ok'
        });
    } else {
        window.location.href = 'pago.php'; 
    }
});
const offcanvasCarrito = document.getElementById('offcanvasCarrito');
if (offcanvasCarrito) {
    offcanvasCarrito.addEventListener('show.bs.offcanvas', () => {
        renderizarCarrito();
    });
}

actualizarContador();
