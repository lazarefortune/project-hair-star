window.onload = () => {
    let deleteImageRealisationLinks = document.querySelectorAll("[data-delete-image-realisation]");

    deleteImageRealisationLinks.forEach((link) => {
        link.addEventListener('click', (e) => {
            e.preventDefault();

            if (!confirm('Voulez-vous vraiment supprimer cette image ?')) {
                return;
            }

            let imageId = link.dataset.id;
            let image = document.querySelector('#image-realisation-' + imageId);
            let url = link.href;
            let token = link.dataset.token;

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({"_token": token})
            }).then(
                response => response.json()
                // response => console.log(response.json())
            ).then((data) => {
                if (data.success) {
                    image.remove();
                } else {
                    alert(data.error);
                }
            }).catch((e) => alert(e));
        });
    });
}