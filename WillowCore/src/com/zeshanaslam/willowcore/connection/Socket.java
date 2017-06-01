package com.zeshanaslam.willowcore.connection;

import com.zeshanaslam.willowcore.Main;
import org.bukkit.Bukkit;
import org.bukkit.ChatColor;
import org.bukkit.entity.Player;
import org.java_websocket.WebSocket;
import org.java_websocket.handshake.ClientHandshake;
import org.java_websocket.server.WebSocketServer;

import java.net.InetSocketAddress;
import java.util.HashMap;

public class Socket extends WebSocketServer {

    private HashMap<WebSocket, Boolean> connections;

    public Socket() {
        super(new InetSocketAddress(Main.config.port));
        connections = new HashMap<>();
    }

    @Override
    public void onOpen(WebSocket conn, ClientHandshake handshake) {
        connections.put(conn, false);

        conn.send("VERIFY");
    }

    @Override
    public void onClose(WebSocket conn, int code, String reason, boolean remote) {
        connections.remove(conn);
    }

    @Override
    public void onMessage(WebSocket conn, String message) {
        boolean isValid = connections.get(conn);

        if (isValid) {
            message = ChatColor.translateAlternateColorCodes('&', message);
        } else {
            String[] info = message.split("<>");

            if (Main.sql.isValidUser(info[0], info[1])) {
                connections.remove(conn);
                connections.put(conn, true);

                // Update with online users
                for (Player player : Bukkit.getOnlinePlayers()) {
                    conn.send("JOIN: " + player.getName());
                }
            }
        }
    }

    @Override
    public void onError(WebSocket conn, Exception ex) {
        if (conn != null) {
            connections.remove(conn);
        }
    }

    public void sendMessage(String message) {
        for (WebSocket socket: connections.keySet()) {
            if (connections.get(socket)) {
                socket.send(message);
            } else {
                socket.send("VERIFY");
            }
        }
    }
}