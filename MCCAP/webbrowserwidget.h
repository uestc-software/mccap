#ifndef WEBBROWSERWIDGET_H
#define WEBBROWSERWIDGET_H

#include <QWidget>

namespace Ui {
class WebBrowserWidget;
}

class WebBrowserWidget : public QWidget
{
    Q_OBJECT

public:
    explicit WebBrowserWidget(QWidget *parent = 0);
    ~WebBrowserWidget();

public slots:
    void on_call_web_open_url(QString url);

signals:
    void changePage(int);

private slots:
    void on_homeBtn_clicked();
    void on_webView_linkClicked(const QUrl &url);

private:
    Ui::WebBrowserWidget *ui;
};

#endif // WEBBROWSERWIDGET_H
