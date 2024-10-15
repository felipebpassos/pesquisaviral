document.addEventListener("DOMContentLoaded", function () {

    const circle = document.querySelector(".progress-ring__circle");

    const circumference = 2 * Math.PI * circle.getAttribute("r");

    circle.style.strokeDasharray = `${circumference} ${circumference}`;
    circle.style.strokeDashoffset = `${circumference - (percentage / 100) * circumference}`;

    console.log(circumference - (percentage / 100) * circumference)

});