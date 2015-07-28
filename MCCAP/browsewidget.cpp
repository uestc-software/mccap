#include "browsewidget.h"
#include "ui_browsewidget.h"
#include "organismhdl.h"

BrowseWidget::BrowseWidget(QWidget *parent) :
    QWidget(parent),
    ui(new Ui::BrowseWidget)
{
    ui->setupUi(this);
    initTree();

    //to deal with Responsive Layout
    QGridLayout *responLayout = new QGridLayout(this);
    responLayout->addWidget(ui->gridLayoutWidget);
}

BrowseWidget::~BrowseWidget()
{
    delete ui;
}

void BrowseWidget::on_call_clusterServ_result(QList<ClusterHdl> list)
{
//    ClusterHdl *data;
    for(int i = 0; i < list.size(); i++) {
        QStringList record;
        record << list.at(i).accessNum << list.at(i).description;
        QTreeWidgetItem *node = ui->browse->topLevelItem(list.at(i).category - 1);
        node->addChild(new QTreeWidgetItem(node, record));
    }
}

void BrowseWidget::initTree()
{
    QStringList treeLabel, cateName;
    treeLabel << QObject::tr("Cluster of essential gene") << QObject::tr("Function description");
    ui->browse->clear();
    ui->browse->setColumnWidth(0, 300);
    ui->browse->setColumnCount(2);
    ui->browse->setHeaderLabels(treeLabel);
    treeLabel.clear();
    QTreeWidgetItem *category1 = new QTreeWidgetItem(ui->browse, cateName << QObject::tr("Transport & Metabolism"));
    cateName.clear();
    QTreeWidgetItem *category2 = new QTreeWidgetItem(ui->browse, cateName << QObject::tr("Transcription & Translation"));
    cateName.clear();
    QTreeWidgetItem *category3 = new QTreeWidgetItem(ui->browse, cateName << QObject::tr("General function prediction only"));
    cateName.clear();
    QTreeWidgetItem *category4 = new QTreeWidgetItem(ui->browse, cateName << QObject::tr("Function unknown"));
    cateName.clear();
    QTreeWidgetItem *category5 = new QTreeWidgetItem(ui->browse, cateName << QObject::tr("Others"));
    cateName.clear();
    ui->browse->addTopLevelItem(category1);
    ui->browse->addTopLevelItem(category2);
    ui->browse->addTopLevelItem(category3);
    ui->browse->addTopLevelItem(category4);
    ui->browse->addTopLevelItem(category5);
}

void BrowseWidget::on_homeBtn_clicked()
{
    emit changePage(0);
}
