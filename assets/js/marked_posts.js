document.addEventListener("DOMContentLoaded", () => {

  function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
      "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
  }

  const body = document.querySelector('body'),
    ky_btn = document.querySelector('.ky-clear-btn');

  //watching
  body.addEventListener('click', (event) => {
    const mark = event.target.parentNode;
    parent = mark.parentNode;
    if (mark.classList.contains('favorite-trigger-list')) {
      parent.classList.toggle('ky-post-clear');
    }
  })

  ky_btn.addEventListener('click', (event) => {
    const posts_clear = document.querySelectorAll('.ky-post-clear');
    const all_posts = document.querySelectorAll('.ky-favorite-posts-item');
    if (posts_clear.length === all_posts.length) {
      ky_btn.style.display = 'none';
    }

    posts_clear.forEach(function (item) {
      item.style.display = 'none';

      let post_id = item.getAttribute('data-ky-post-id');
      post_id = Number(post_id);

      let arr = JSON.parse(getCookie('wp-ky-data')),
        index = arr.indexOf(post_id);

      arr.splice(index, 1);
      arr = JSON.stringify(arr);
      document.cookie = `wp-ky-data=${arr}; path=/`;
    });
  });

});


