$('#add-image').click(function () {
    //je récupére le numéro des futures champs à créer
    const index= +$('#widgets-counter').val();

    //je récupère le prototype
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);
    //j'injecte ce code au sein de la div
    $('#ad_images').append(tmpl);
    $('#widgets-counter').val(index + 1);
    //je gère le boutton supprimer
    handleDeleteButtons();
})
function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;
        $(target).remove();

    })

}
function updateCounter() {
    const count= +$('#ad_images div.form-group').length;
    $('#widgets-counter').val(count);

}
updateCounter();
handleDeleteButtons();