#include "serverconnector.h"
#include "organismhdl.h"

ServerConnector::ServerConnector(QObject *parent) : QObject(parent)
{
    prepareNetMgr();
    connect(clusterMgr, SIGNAL(finished(QNetworkReply*)), this, SLOT(on_clusterMgr_finished(QNetworkReply*)));
    connect(wholePwMgr, SIGNAL(finished(QNetworkReply*)), this, SLOT(on_wholePwMgr_finsihed(QNetworkReply*)));
    connect(metaPwMgr, SIGNAL(finished(QNetworkReply*)), this, SLOT(on_metaPwMgr_finished(QNetworkReply*)));
    connect(wholeMdMgr, SIGNAL(finished(QNetworkReply*)), this, SLOT(on_wholeMdMgr_finished(QNetworkReply*)));
    connect(metaMdMgr, SIGNAL(finished(QNetworkReply*)), this, SLOT(on_metaMdMgr_finished(QNetworkReply*)));
}

ServerConnector::~ServerConnector()
{
    delete clusterMgr;
    delete wholePwMgr;
    delete metaPwMgr;
    delete wholeMdMgr;
    delete metaMdMgr;
}

void ServerConnector::call_clusterServ(QList<QString> list)
{
    OrganismHdl *ogHdl = new OrganismHdl;
    QString preUrl = serverHost + QString("api/cegRecord/fetchCeg.php?id=");
    QString str, param;
    foreach (str, list) {
        param.append(ogHdl->ogMap[str].trimmed());
    }
    QString url = preUrl + param;
    req.setUrl(QUrl(url));
    clusterMgr->get(req);
}

void ServerConnector::call_wholePwServ(QList<QString> list)
{
    OrganismHdl *ogHdl = new OrganismHdl;
    QString preUrl = serverHost + QString("api/kegg/entirePathway.php?id=");
    QString str, param;
    foreach (str, list) {
        param.append(ogHdl->ogMap[str].trimmed());
    }
    QString url = preUrl + param;
    req.setUrl(QUrl(url));
    wholePwMgr->get(req);
}

void ServerConnector::call_metaPwServ(QList<QString> list)
{
    OrganismHdl *ogHdl = new OrganismHdl;
    QString preUrl = serverHost + QString("api/kegg/metabolicPathway.php?id=");
    QString str, param;
    foreach (str, list) {
        param.append(ogHdl->ogMap[str].trimmed());
    }
    QString url = preUrl + param;
    req.setUrl(QUrl(url));
    metaPwMgr->get(req);
}

void ServerConnector::call_wholeMdServ(QList<QString> list)
{
    OrganismHdl *ogHdl = new OrganismHdl;
    QString preUrl = serverHost + QString("api/kegg/entireModule.php?id=");
    QString str, param;
    foreach (str, list) {
        param.append(ogHdl->ogMap[str].trimmed());
    }
    QString url = preUrl + param;
    req.setUrl(QUrl(url));
    wholeMdMgr->get(req);
}

void ServerConnector::call_metaMdServ(QList<QString> list)
{
    OrganismHdl *ogHdl = new OrganismHdl;
    QString preUrl = serverHost + QString("api/kegg/metabolicModule.php?id=");
    QString str, param;
    foreach (str, list) {
        param.append(ogHdl->ogMap[str].trimmed());
    }
    QString url = preUrl + param;
    req.setUrl(QUrl(url));
    metaMdMgr->get(req);
}

void ServerConnector::on_clusterMgr_finished(QNetworkReply *reply)
{
    int status;
    QTextCodec *codec = QTextCodec::codecForName("utf8");
    QString content = codec->toUnicode(reply->readAll());
    jDoc = QJsonDocument::fromJson(content.toUtf8(), &jErr);
    if(jErr.error == QJsonParseError::NoError) {
        if(jDoc.isObject()) {
            QVariantMap objMap = jDoc.toVariant().toMap();
            status = objMap["status"].toInt();
            if(!status) {
                QList<QVariant> dataList = objMap["data"].toList();
                QList<ClusterHdl> list;
                ClusterHdl c;
                for(int i = 0; i < dataList.size(); i++) {
                    QVariantMap data = dataList.at(i).toMap();
                    int category = data["category"].toInt();
                    QString cluster, description;
                    cluster = data["cluster"].toString();
                    description = data["description"].toString();
                    c.category = category;
                    c.accessNum = cluster;
                    c.description = description;
                    list.append(c);
                }
                emit call_clusterServ_result(list);
            }
        }
    }else {
        qDebug() << QString("Error!");
    }
}

void ServerConnector::on_wholePwMgr_finsihed(QNetworkReply *reply)
{
    int status;
    QTextCodec *codec = QTextCodec::codecForName("utf8");
    QString content = codec->toUnicode(reply->readAll());
    jDoc = QJsonDocument::fromJson(content.toUtf8(), &jErr);
    if(jErr.error == QJsonParseError::NoError) {
        if(jDoc.isObject()) {
            QVariantMap objMap = jDoc.toVariant().toMap();
            status = objMap["status"].toInt();
            if(!status) {
                QString url = objMap["data"].toString();
                emit call_web_open_url(url);
            }
        }
    }else {
        qDebug() << QString("Error!");
    }
}

void ServerConnector::on_metaPwMgr_finished(QNetworkReply *reply)
{
    int status;
    QTextCodec *codec = QTextCodec::codecForName("utf8");
    QString content = codec->toUnicode(reply->readAll());
    jDoc = QJsonDocument::fromJson(content.toUtf8(), &jErr);
    if(jErr.error == QJsonParseError::NoError) {
        if(jDoc.isObject()) {
            QVariantMap objMap = jDoc.toVariant().toMap();
            status = objMap["status"].toInt();
            if(!status) {
                QString url = objMap["data"].toString();
                emit call_web_open_url(url);
            }
        }
    }else {
        qDebug() << QString("Error!");
    }
}

void ServerConnector::on_wholeMdMgr_finished(QNetworkReply *reply)
{
    int status;
    QTextCodec *codec = QTextCodec::codecForName("utf8");
    QString content = codec->toUnicode(reply->readAll());
    jDoc = QJsonDocument::fromJson(content.toUtf8(), &jErr);
    if(jErr.error == QJsonParseError::NoError) {
        if(jDoc.isObject()) {
            QVariantMap objMap = jDoc.toVariant().toMap();
            status = objMap["status"].toInt();
            if(!status) {
                QString url = objMap["data"].toString();
                emit call_web_open_url(url);
            }
        }
    }else {
        qDebug() << QString("Error!");
    }
}

void ServerConnector::on_metaMdMgr_finished(QNetworkReply *reply)
{
    int status;
    QTextCodec *codec = QTextCodec::codecForName("utf8");
    QString content = codec->toUnicode(reply->readAll());
    jDoc = QJsonDocument::fromJson(content.toUtf8(), &jErr);
    if(jErr.error == QJsonParseError::NoError) {
        if(jDoc.isObject()) {
            QVariantMap objMap = jDoc.toVariant().toMap();
            status = objMap["status"].toInt();
            if(!status) {
                QString url = objMap["data"].toString();
                emit call_web_open_url(url);
            }
        }
    }else {
        qDebug() << QString("Error!");
    }
}

void ServerConnector::prepareNetMgr()
{
    clusterMgr = new QNetworkAccessManager;
    wholePwMgr = new QNetworkAccessManager;
    metaPwMgr = new QNetworkAccessManager;
    wholeMdMgr = new QNetworkAccessManager;
    metaMdMgr = new QNetworkAccessManager;
}






