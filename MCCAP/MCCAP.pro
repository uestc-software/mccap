#-------------------------------------------------
#
# Project created by QtCreator 2015-07-20T11:25:36
#
#-------------------------------------------------

QT       += core gui network webkit webkitwidgets

greaterThan(QT_MAJOR_VERSION, 4): QT += widgets

TARGET = MCCAP
TEMPLATE = app


SOURCES += main.cpp\
        mainwidget.cpp \
    entrywidget.cpp \
    browsewidget.cpp \
    util.cpp \
    organismhdl.cpp \
    serverconnector.cpp \
    webbrowserwidget.cpp \
    clusterhdl.cpp

HEADERS  += mainwidget.h \
    entrywidget.h \
    browsewidget.h \
    util.h \
    organismhdl.h \
    serverconnector.h \
    webbrowserwidget.h \
    clusterhdl.h

FORMS    += mainwidget.ui \
    entrywidget.ui \
    browsewidget.ui \
    webbrowserwidget.ui
