#include "mainwidget.h"
#include "ui_mainwidget.h"
#include "entrywidget.h"
#include "browsewidget.h"
#include "webbrowserwidget.h"
#include "serverconnector.h"

MainWidget::MainWidget(QWidget *parent) :
    QWidget(parent),
    ui(new Ui::MainWidget)
{
    ui->setupUi(this);

    //to deal with Responsive Layout
    QGridLayout *responLayout = new QGridLayout(this);
    responLayout->addWidget(ui->gridLayoutWidget);
    //take out title bar and border
//    setWindowFlags(Qt::FramelessWindowHint);

    //add entry widget to main interface
    EntryWidget *entryWgt = new EntryWidget(ui->entryPage);
    QGridLayout *entryPageLayout = new QGridLayout(ui->entryPage);
    entryPageLayout->addWidget(entryWgt);
    //add browse widget to main interface
    BrowseWidget *browseWgt = new BrowseWidget(ui->browsePage);
    QGridLayout *browsePageLayout = new QGridLayout(ui->browsePage);
    browsePageLayout->addWidget(browseWgt);
    //add web browser to main interface
    WebBrowserWidget *webWgt = new WebBrowserWidget(ui->webPage);
    QGridLayout *webPageLayout = new QGridLayout(ui->webPage);
    webPageLayout->addWidget(webWgt);
    //add server connector
    ServerConnector *servConn = new ServerConnector;

    //set connector
    connect(entryWgt, SIGNAL(generateClusterList(QList<QString>)), servConn, SLOT(call_clusterServ(QList<QString>)));
    connect(entryWgt, SIGNAL(generateMetaModule(QList<QString>)), servConn, SLOT(call_metaMdServ(QList<QString>)));
    connect(entryWgt, SIGNAL(generateMetaPathway(QList<QString>)), servConn, SLOT(call_metaPwServ(QList<QString>)));
    connect(entryWgt, SIGNAL(generateWholeModule(QList<QString>)), servConn, SLOT(call_wholeMdServ(QList<QString>)));
    connect(entryWgt, SIGNAL(generateWholePathway(QList<QString>)), servConn, SLOT(call_wholePwServ(QList<QString>)));
    connect(entryWgt, SIGNAL(changePage(int)), ui->stackedWidget, SLOT(setCurrentIndex(int)));
    connect(browseWgt, SIGNAL(changePage(int)), ui->stackedWidget, SLOT(setCurrentIndex(int)));
    connect(webWgt, SIGNAL(changePage(int)), ui->stackedWidget, SLOT(setCurrentIndex(int)));
    connect(servConn, SIGNAL(call_clusterServ_result(QList<ClusterHdl>)), browseWgt, SLOT(on_call_clusterServ_result(QList<ClusterHdl>)));
    connect(servConn, SIGNAL(call_web_open_url(QString)), webWgt, SLOT(on_call_web_open_url(QString)));
}

MainWidget::~MainWidget()
{
    delete ui;
}
