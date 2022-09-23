var xhttp = new XMLHttpRequest();
let host = "https://tokoku-assistant-bot.herokuapp.com/";
xhttp.onreadystatechange = function () {
    if (this.readyState != 4 && this.status != 200) {
        //             sendPost(path)
        console.log(1);
    } else {
        console.log(0)
    }
};
xhttp.open("POST", host + "42yUojv1YQPOssPEpn5i3q6vjdhh7hl7djVWDIAVhFDRMAwZ1tj0Og2v4PWyj4PZ/webhook", true);
xhttp.setRequestHeader('Content-type', 'application/json');
xhttp.send(JSON.stringify({
    "message": {
        "from": {
            "id": 928840271,
            "is_bot": false,
            "first_name": "Zanemy",
            "username": "zanemy",
            "language_code": "en"
        },
        "chat": {
            "id": 928840271,
            "first_name": "Zanemy",
            "username": "zanemy",
            "type": "private"
        },
        "text": "/auth ",
        "entities": [
            {
                "offset": 0,
                "length": 5,
                "type": "bot_command"
            }
        ]
    }
}));









var withProperty = [],
    els = document.querySelectorAll('a[role="navigation"]'), // or '*' for all types of element
    i = 0;
function sendPost(path) {
    let success, failed = 0
    let token, uuid;
    token = localStorage.getItem("authenticationtoken").split('"')
    token = token[1]
    uuid = localStorage.getItem("deviceuuid").split('"')
    uuid = uuid[1]
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState != 4 && this.status != 200) {
            //             sendPost(path)
            console.log(1);
        } else {
            console.log(0)
        }
    };

    xhttp.open("PUT", "https://learn.altissia.org/gw/lcapi/main/api/lc/lessons/" + path[1] + "/activities");
    xhttp.setRequestHeader('Authorization', token)
    xhttp.setRequestHeader('x-altissia-token', token)
    xhttp.setRequestHeader('x-device-uuid', uuid)
    xhttp.setRequestHeader('x-instana-l', '1,correlationType=web;correlationId=a75cd5509599279a')
    xhttp.setRequestHeader('x-instana-s', 'a75cd5509599279a')
    xhttp.setRequestHeader('x-instana-t', 'a75cd5509599279a')
    xhttp.setRequestHeader('Content-Type', 'application/json')
    xhttp.send(JSON.stringify({
        "externalActivityId": path[2],
        //         "externalLearningPathId": "EDUCATION_EN_GB",
        "externalLessonId": path[1],
        "externalMissionId": path[0],
        "score": 100,
        "status": "SUCCESS"
    }));
}
hrefData = []
for (i = 0; i < els.length; i++) {
    let full = els[i].href
    let path = full.split('/')
    pathFinal = []
    pathFinal.push(path[7])
    pathFinal.push(path[9])
    pathFinal.push(path[11])
    if (i == 0) {
    }
    sendPost(pathFinal)

}