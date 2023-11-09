let myModal = document.getElementById('myModal')
let myInput = document.getElementById('myInput')
let packButton = document.getElementById('packButton')

const showModal = (packId) => {
    packButton.setAttribute('href',`/Reservationpack/${packId}`);
    myInput.focus()
}