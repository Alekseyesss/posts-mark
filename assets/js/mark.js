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
    const mark = event.target.parentNode;
    if (mark.classList.contains('favorite-trigger')) {
      let post_id = mark.getAttribute('data-ky-post-id');
      post_id = Number(post_id);

      mark.classList.toggle('ky-favorite');

      let arr = [];
      if (getCookie('wp-ky-data')) {
        arr = JSON.parse(getCookie('wp-ky-data'));
        let index = arr.indexOf(post_id);
        (index === -1) ? arr.unshift(post_id) : arr.splice(index, 1);
      }
      else {
        arr.push(post_id);
      }
      arr = JSON.stringify(arr);
      document.cookie = `wp-ky-data=${arr}; path=/; max-age=315360000`;
    }
  })
});