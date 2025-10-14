// var windowWidth = ((window.innerWidth*0.78)/4)-32;
// var test = document.querySelector('.test').children;
// test = Array.from(test);
// test.forEach(elem => {
//     elem.style.width = test + "px";
//     console.log(test)
// })
// console.log(windowWidth)

function getTranslateX(element) {
    if (!element) return 0;
    const style = window.getComputedStyle(element);
    const transform = style.transform || style.webkitTransform || style.mozTransform;

    if (!transform || transform === 'none') {
        return 0;
    }
    if (transform.includes('matrix')) {
        const matrix = new DOMMatrix(transform);
        return matrix.m41; 
    }
    const translateXMatch = transform.match(/translateX\(([^)]+)\)/);
    if (translateXMatch) {
        return parseFloat(translateXMatch[1]);
    }
    return 0;
}





var leftBtn = document.querySelectorAll('.left-btn');
var rightBtn = document.querySelectorAll('.right-btn');
leftBtn = Array.from(leftBtn);
rightBtn = Array.from(rightBtn);

leftBtn.forEach(btn => {
    btn.addEventListener('click', () => {
        let target = btn.nextElementSibling.firstElementChild;
        let currentX = getTranslateX(target)
        let trans = currentX+220;

        if (trans > 0) {
            trans = 0;
            btn.style.visibility = 'hidden'
        } 
        target.style.transition = 'transform 0.3s ease-in-out';
        target.style.transform = `translateX(${trans}px)`
        // console.log(target.getBoundingClientRect().width)
        // console.log(window.innerWidth * 0.78)
    })
});
rightBtn.forEach(btn => {
    btn.addEventListener('click', () => {
        let target = btn.previousElementSibling.firstElementChild
        let currentX = getTranslateX(target)
        let trans = currentX-220;

        // console.log(windowWidth - trans)
        let windowWidth = window.innerWidth * 0.78;
        let targetWidth = target.getBoundingClientRect().width;
        if (windowWidth - trans > targetWidth) {
            trans = -(targetWidth - windowWidth);
            // btn.outerHTML = `<button
            //         class="left-btn absolute right-[-15px] top-[50%] translate-y-[-50%] bg-white  border-black border-2 rounded-full w-[40px] h-[40px]"><i
            //             class="fa-solid fa-arrow-left text-xl"></i><i
            //             class="fa-solid fa-arrow-left text-xl"></i></button>`
        }
        if (trans > 0) trans = 0;
        target.style.transition = 'transform 0.3s ease-in-out';
        target.style.transform = `translateX(${trans}px)`
        let left = btn.previousElementSibling.previousElementSibling
        left.style.visibility = 'visible'
    })
});





function show(element, section) {
    let allBtn = document.querySelectorAll('.btn-show');
    allBtn = Array.from(allBtn);
    // console.log(allBtn)
    allBtn.forEach(btn => {
        if(btn == element) console.log('continue');
        else if (btn.innerText == 'See less ') btn.click();
    });
    let target = element.previousElementSibling;
    if (element.innerText == "See more ") {
        target.style.transition = 'height 13s ease-in-out';
        target.style.height = "auto";
        element.innerHTML = "See less <i class='fa fa-caret-up'></i>";
    }
    else {
        target.style.transition = 'height 31s ease-in-out';
        target.style.height = "200px";
        element.innerHTML = "See more <i class='fa fa-caret-down'></i>";
    }

    if (section != 'Category') {
        document.querySelector('.current-sold-items').classList.toggle('col-span-2')
    }
    else {
        document.querySelector('.current-sold-items').classList.toggle('col-span-2')
        // document.querySelector('.Bargains-galore').classList.toggle('col-span-2')
    }
    // console.log(section)
}