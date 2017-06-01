package com.zeshanaslam.willowcore;

import org.apache.logging.log4j.core.LogEvent;
import org.apache.logging.log4j.core.appender.AbstractAppender;
import org.apache.logging.log4j.core.layout.PatternLayout;

import java.text.SimpleDateFormat;


public class CaptureConsole extends AbstractAppender {

    private Main main;

    public CaptureConsole(Main main) {
        super("Log4JAppender", null,
                PatternLayout.createLayout(
                        "[%d{HH:mm:ss} %level]: %msg",
                        null, null, null, null), false);

        this.main = main;
    }

    @Override
    public boolean isStarted() {
        return true;
    }

    @Override
    public void append(LogEvent e) {
        if (main.socket != null) {
            SimpleDateFormat simpleDateFormat = new SimpleDateFormat("HH:mm:ss");

            main.socket.sendMessage("[" + simpleDateFormat.format(e.getMillis()) + " " + e.getLevel().name() + "]: " + e.getMessage().getFormat());
        }
    }
}
