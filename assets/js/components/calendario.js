console.log("JavaScript is running!");

const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
const daysInMonth = (month, year) => {
    console.log(`Calculating days in month for month: ${month}, year: ${year}`);
    return new Date(year, month + 1, 0).getDate();
};
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

document.addEventListener('DOMContentLoaded', () => {
    console.log("DOM fully loaded and parsed");
    renderCalendar(currentMonth, currentYear);

    const prevButton = document.getElementById('prevMonth');
    const nextButton = document.getElementById('nextMonth');

    console.log("Adding event listeners to buttons");
    prevButton.addEventListener('click', () => {
        console.log("Prev button clicked");
        changeMonth(-1);
    });
    nextButton.addEventListener('click', () => {
        console.log("Next button clicked");
        changeMonth(1);
    });
});

function renderCalendar(month, year) {
    console.log("Executing renderCalendar function");
    const monthYear = document.getElementById('monthYear');
    monthYear.textContent = `${monthNames[month]} ${year}`;

    const calendarBody = document.getElementById('calendarBody');
    console.log("Clearing calendar body");
    calendarBody.innerHTML = '';

    const firstDay = new Date(year, month).getDay();
    const totalDays = daysInMonth(month, year);

    console.log(`Rendering calendar for ${monthNames[month]} ${year}`);
    console.log(`First day of the month is on: ${firstDay}`);
    console.log(`Total days in the month: ${totalDays}`);

    let date = 1;
    for (let i = 0; i < 6; i++) {
        const row = document.createElement('tr');
        console.log(`Creating row ${i}`);

        for (let j = 0; j < 7; j++) {
            const cell = document.createElement('td');
            if (i === 0 && j < firstDay) {
                cell.textContent = '';
                console.log(`Row ${i} Col ${j}: Empty`);
            } else if (date > totalDays) {
                cell.textContent = '';
                console.log(`Row ${i} Col ${j}: Empty`);
            } else {
                cell.textContent = date;
                console.log(`Row ${i} Col ${j}: ${date}`);
                date++;
            }
            row.appendChild(cell);
        }

        calendarBody.appendChild(row);
        console.log(`Row ${i} added to calendar`);
    }
}

function changeMonth(step) {
    currentMonth += step;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    console.log(`Changing month to: ${currentMonth}, year to: ${currentYear}`);
    renderCalendar(currentMonth, currentYear);
}
