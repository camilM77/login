const http = require("http");
const port = 3000;

const server = http.createServer((req, res) => {
    console.log("he entrado en el createServer")
    res.statusCode == 200;
    res.setHeader("Content-Type", "text/plain");
    //finalizar la conexion
    res.end("hola mundo uwu");
});

server.listen(port, () => {
    console.log(`he arrancado en el puerto : ${port}`)
})
