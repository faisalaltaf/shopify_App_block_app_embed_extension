console.log('scripttag hello');

var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
       // Typical action to be performed when the document is ready:

    }
};
xhttp.open("GET", "filename", true);
xhttp.send();

// const templateJSONFiles = assets.filter((file) => {
//     return APP_BLOCK_TEMPLATES.some(template => file.key === `templates/${template}.json`);
//   });