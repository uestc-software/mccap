#ifndef ENTRYWIDGET_H
#define ENTRYWIDGET_H

#include <QWidget>
#include <qbuttongroup.h>

namespace Ui {
class EntryWidget;
}

class EntryWidget : public QWidget
{
    Q_OBJECT

public:
    explicit EntryWidget(QWidget *parent = 0);
    ~EntryWidget();

signals:
    void generateClusterList(QList<QString>);
    void generateWholePathway(QList<QString>);
    void generateMetaPathway(QList<QString>);
    void generateWholeModule(QList<QString>);
    void generateMetaModule(QList<QString>);
    void changePage(int);

private slots:
    void on_btnGp1Changed(int id);
    void on_organismSelect_doubleClicked(const QModelIndex &index);
    void on_organismSelected_doubleClicked(const QModelIndex &index);
    void on_submit_clicked();

private:
    Ui::EntryWidget *ui;
    QButtonGroup *btnGp1, *btnGp2;
    void initList();
    bool validateSelected();
    bool validateResultType();
    bool validateOption();
    int getResultType();
    int getOption();
    void execute();
};

#endif // ENTRYWIDGET_H
