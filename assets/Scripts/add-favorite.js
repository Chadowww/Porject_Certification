const test = document.querySelectorAll('.bt-fav');

function addToFavorite(event) {
    event.preventDefault();

    const favLink = event.currentTarget;
    const link = favLink.href;

    try{
        fetch(link)
            .then(response => response.json())
            .then(data => {
                const favIcon = favLink.firstElementChild;
                if(data.isFavorite){
                    favIcon.classList.remove('bi-heart');
                    favIcon.classList.add('bi-heart-fill');
                }else {
                    favIcon.classList.remove('bi-heart-fill');
                    favIcon.classList.add('bi-heart');
                }
            });
    }catch(e){
        console.log(e);
    }

}
window.addToFavorite = addToFavorite;