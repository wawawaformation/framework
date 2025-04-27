const formElt = document.querySelector('form')
const rgpdElt = document.querySelector('#rgpd')

formElt.addEventListener('submit', function (e) {
  if (!rgpdElt.checked) {
    e.preventDefault()
    alert("Veuillez accepter la politique des données personnelles.")
  }
})