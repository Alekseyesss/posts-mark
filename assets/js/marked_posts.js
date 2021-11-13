document.addEventListener("DOMContentLoaded", () => {

  function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
      "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
  }

  const body = document.querySelector('body');

  //watching
  body.addEventListener('click', (event) => {
    const mark = event.target.parentNode,
      parent = mark.parentNode;
    if (mark.classList.contains('favorite-trigger-list')) {
      if (parent.style.opacity == 0.4) {
        parent.style.display = 'none';
        let post_id = mark.getAttribute('data-ky-post-id');
        post_id = Number(post_id);

        let arr = JSON.parse(getCookie('wp-ky-data')),
          index = arr.indexOf(post_id);

        arr.splice(index, 1);
        arr = JSON.stringify(arr);
        document.cookie = `wp-ky-data=${arr}; path=/`;
      }
      parent.style.opacity = 0.4;
    }
  })
});


