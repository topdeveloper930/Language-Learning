document.addEventListener("DOMContentLoaded", function(){
    const tabLinks = document.querySelectorAll('.tab-navigation a[href*="#"]'),
        hide = id => document.getElementById(id).setAttribute('hidden', 1),
        show = id => document.getElementById(id).removeAttribute('hidden' ),
        clickHandler = e => {
            const anchor = e.currentTarget;
            e.preventDefault();
            anchor.closest('.tab-navigation').querySelectorAll('a').forEach(l => {
                if( l === anchor ) {
                    l.classList.add('active');
                    show(l.getAttribute('href').substr(1));
                }
                else {
                    l.classList.remove('active');
                    hide(l.getAttribute('href').substr(1));
                }
            } );
        };

    if(!tabLinks.length) return;

    tabLinks.forEach(link => {
        link.addEventListener('click', clickHandler, false);
        if(!link.classList.contains('active'))
            hide(link.getAttribute('href').substr(1));
    })
});
