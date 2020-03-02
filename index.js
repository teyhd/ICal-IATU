const ical = require('ical-generator');
const http = require('http');
const moment = require('moment');
var request = require('request');
var rp = require('request-promise');
const cal = ical({domain: 'ser.teyhd.ru', name: 'iCaапаl'});

cal.domain('ser.teyhd.ru');

function creator(param){
    console.log(param);
    for (let i=0;i<param.length;i++){
        
        cal.createEvent({
            start: new Date(param[i].start),
            end: new Date(param[i].end),
            summary: param[i].subject,
            description: param[i].subject,
            location: 'ИАТУ'
        });
    }
    return true;
}
function rasp(dates){
    var options = {
    method: 'POST',
    uri: 'http://localhost/ical/test.php',
    form: {
        date: dates
    },
    headers: {
        /* 'content-type': 'application/x-www-form-urlencoded' */ // Is set automatically
    }
};
 
rp(options)
    .then(function (body) {
        let answ="";
            body = JSON.parse(body);
            creator(body);
            return true;
    })
    .catch(function (err) {
        //ans("Произошла ошибка получения пар");
    });
    
    //return answ;
} //Get запрос достает расписание 
//http://89.113.242.41:3000/calendar.ics
process.on('uncaughtException', (err) => {
  console.log('whoops! there was an error', err.stack);
}); //Если все пошло по пизде, спасет ситуацию
/*
            start: "2020-03-01T19:06:45.040",
            end: moment().add(5, 'hour'),
            summary: 'Example Event',
            description: 'It works ;)',
            location: 'ИАТУ'*/
            
var events = ["2020-03-02","2020-03-03","2020-03-04","2020-03-05","2020-03-06","2020-03-07"]; 
for(let i=0;i<events.length;i++){
    setTimeout(function() {rasp(events[i]); }, 1000*2);
}
    http.createServer(function(req, res) {
    cal.serve(res);
}).listen(3000, function() {
    console.log('Server running at http://127.0.0.1:3000/');
});