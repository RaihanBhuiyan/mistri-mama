const localStorageService = {
    setItem: function(key, data) {
        localStorage.setItem(key, JSON.stringify(data));
    },
    getItem: function(key) {
        let data = localStorage.getItem(key) || null;
        return JSON.parse(data);
    }
};

const Helper = {
    findItemArr(array, key, search) {
            return this.array.find(array => array.key == search);
        },
        groupBy(list, keyGetter) {
            const map = new Map();
            list.forEach(item => {
                const key = keyGetter(item);
                const collection = map.get(key);
                if (!collection) {
                    map.set(key, [item]);
                } else {
                    collection.push(item);
                }
            });
            return map;
        }
};

const customDate = {
    MonthAsString: function(monthIndex) {
        var d = new Date();
        var month = new Array();
        month[0] = "January";
        month[1] = "February";
        month[2] = "March";
        month[3] = "April";
        month[4] = "May";
        month[5] = "June";
        month[6] = "July";
        month[7] = "August";
        month[8] = "September";
        month[9] = "October";
        month[10] = "November";
        month[11] = "December";

        return month[monthIndex];
    },

    DayAsString: function(dayIndex) {
        var weekdays = new Array(7);
        weekdays[0] = "Sunday";
        weekdays[1] = "Monday";
        weekdays[2] = "Tuesday";
        weekdays[3] = "Wednesday";
        weekdays[4] = "Thursday";
        weekdays[5] = "Friday";
        weekdays[6] = "Saturday";

        return weekdays[dayIndex];
    },

    GetDates: function(startDate, daysToAdd) {
        var aryDates = [];

        for (var i = 0; i <= daysToAdd; i++) {
            var currentDate = new Date();
            currentDate.setDate(startDate.getDate() + i);
            aryDates.push(
                this.DayAsString(currentDate.getDay()) +
                ", " +
                currentDate.getDate() +
                " " +
                this.MonthAsString(currentDate.getMonth()) +
                " " +
                currentDate.getFullYear()
            );
        }

        return aryDates;
    }
};

//console.log(aryDates);

export {
    localStorageService, Helper, customDate
};