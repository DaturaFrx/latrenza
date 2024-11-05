let clickCount = 0;
const audio = new Audio('<?php echo SITE_URL; ?>/files/jt.mp3');

document.getElementById('clickable-rights').addEventListener('click', () => {
    clickCount++;
    if (clickCount === 10) {
        audio.play();
        clickCount = 0;
    }
    setTimeout(() => clickCount = 0, 2000);
});
