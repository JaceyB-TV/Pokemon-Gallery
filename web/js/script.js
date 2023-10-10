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
    const x = document.getElementById("links");

    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
};

const setSearchParam = (key, value) => {
    const existing = window.location.search,
        searchParams = {};

    existing.split("&").forEach(s => {
        const sp = s.split("=");
        searchParams[sp[0].startsWith("?") ? sp[0].substring(1) : sp[0]] = sp[1];
    });

    if (searchParams[key] === value) {
        return;
    }

    if (key && value) {
        searchParams[key] = value;
    }

    let search = "";
    Object.keys(searchParams).forEach(key => {
        if (!key || !searchParams[key]) {
            return;
        }

        if (search.length) {
            search += "&";
        }

        search += key + "=" + searchParams[key];
    });

    window.location.search = "?" + search;
};

const sort = (value) => {
    setSearchParam("sort", value);
};
