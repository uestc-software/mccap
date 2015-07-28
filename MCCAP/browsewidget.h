#ifndef BROWSEWIDGET_H
#define BROWSEWIDGET_H

#include <QWidget>
#include "clusterhdl.h"

namespace Ui {
class BrowseWidget;
}

class BrowseWidget : public QWidget
{
    Q_OBJECT

public:
    explicit BrowseWidget(QWidget *parent = 0);
    ~BrowseWidget();

public slots:
    void on_call_clusterServ_result(QList<ClusterHdl> list);

signals:
    void changePage(int);

private slots:
    void on_homeBtn_clicked();

private:
    Ui::BrowseWidget *ui;
    void initTree();
};

#endif // BROWSEWIDGET_H
