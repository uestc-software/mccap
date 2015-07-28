#include "webbrowserwidget.h"
#include "ui_webbrowserwidget.h"

WebBrowserWidget::WebBrowserWidget(QWidget *parent) :
    QWidget(parent),
    ui(new Ui::WebBrowserWidget)
{
    ui->setupUi(this);

    //to deal with Responsive Layout
    QGridLayout *responLayout = new QGridLayout(this);
    responLayout->addWidget(ui->gridLayoutWidget);
    //set property of QWebPage
    ui->webView->page()->setLinkDelegationPolicy(QWebPage::DelegateAllLinks);
}

WebBrowserWidget::~WebBrowserWidget()
{
    delete ui;
}

void WebBrowserWidget::on_call_web_open_url(QString url)
{
    ui->webView->load(QUrl(url));
}

void WebBrowserWidget::on_homeBtn_clicked()
{
    emit changePage(0);
}

void WebBrowserWidget::on_webView_linkClicked(const QUrl &url)
{
    ui->webView->load(url);
}
