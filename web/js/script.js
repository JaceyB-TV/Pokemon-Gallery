const openModal = (a) => {
    const modal = $("#modal-wrapper")[0],
        modalImage = $("#modal-content")[0],
        modalCaption = $("#modal-caption").eq(0);

    const link = $(a),
        image = link.children("div")[0],
        h3 = link.children("h3").eq(0).text(),
        paragraphs = link.children("p"),
        date = paragraphs.eq(0).text(),
        requested = paragraphs.eq(1).text();

    modalImage.src = image.style.backgroundImage.slice(4, -1).replace(/"/g, "");

    modalCaption.html("<span>" + h3 + " " + date + "</span> <span>" + requested + "</span>");

    modal.style.display = "block";
};

const closeModal = () => {
    const modal = $("#modal-wrapper")[0];

    modal.style.display = "none";
};

const toggleMenu = () => {
    var x = document.getElementById("links");
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
};