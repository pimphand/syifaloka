import { io } from "socket.io-client";

const socket = io("http://127.0.0.1:3000", {
    query: {
        token: "your_auth_token", // Sesuaikan dengan token autentikasi
        channel: "general" // Channel yang ingin digunakan
    }
});
socket.on("connect", () => {
    console.log("Connected to WebSocket Server");
});
export default socket;
