#ifndef SERVERCONNECTOR_H
#define SERVERCONNECTOR_H

#include <QObject>
#include <QtWebKitWidgets/QWebView>
#include <QtNetwork>
#include "organismhdl.h"
#include "clusterhdl.h"

class ServerConnector : public QObject
{
    Q_OBJECT
public:
    explicit ServerConnector(QObject *parent = 0);
    ~ServerConnector();

signals:
    void call_clusterServ_result(QList<ClusterHdl>);
    void call_web_open_url(QString);

public slots:
    void call_clusterServ(QList<QString> list);
    void call_wholePwServ(QList<QString> list);
    void call_metaPwServ(QList<QString> list);
    void call_wholeMdServ(QList<QString> list);
    void call_metaMdServ(QList<QString> list);

private slots:
    void on_clusterMgr_finished(QNetworkReply *reply);
    void on_wholePwMgr_finsihed(QNetworkReply *reply);
    void on_metaPwMgr_finished(QNetworkReply *reply);
    void on_wholeMdMgr_finished(QNetworkReply *reply);
    void on_metaMdMgr_finished(QNetworkReply *reply);

private:
    QString serverHost = "http://localhost/mccap-server/";
    QNetworkAccessManager* clusterMgr;
    QNetworkAccessManager* wholePwMgr;
    QNetworkAccessManager* metaPwMgr;
    QNetworkAccessManager* wholeMdMgr;
    QNetworkAccessManager* metaMdMgr;
    QJsonDocument jDoc;
    QJsonParseError jErr;
    QNetworkRequest req;

    void callServer(QString url);
    void prepareNetMgr();
};

#endif // SERVERCONNECTOR_H
