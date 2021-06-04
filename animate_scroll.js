const scrollBtn = document.getElementById('scroll');
const maxHeight = document.documentElement.scrollHeight;
let count = 0;

scrollBtn.addEventListener('click', () => {
    let animateScroll = () => {
        animateId = requestAnimationFrame(animateScroll);
        count = count + 50;
        window.scroll(0, count);
        console.log(count);
        if (count > maxHeight) {
            cancelAnimationFrame(animateId);
        }
    }
    animateScroll();
});

window.addEventListener('scroll', () => {
    count = document.documentElement.scrollTop;
});
