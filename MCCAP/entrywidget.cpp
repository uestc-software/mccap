#include "entrywidget.h"
#include "ui_entrywidget.h"
#include "organismhdl.h"
#include "QMessageBox"

EntryWidget::EntryWidget(QWidget *parent) :
    QWidget(parent),
    ui(new Ui::EntryWidget)
{
    ui->setupUi(this);
    initList();

    //initialize option board.
    btnGp1 = new QButtonGroup();
    btnGp1->addButton(ui->radio1, 1);
    btnGp1->addButton(ui->radio2, 2);
    btnGp1->addButton(ui->radio3, 3);
    btnGp2 = new QButtonGroup();
    btnGp2->addButton(ui->radio4, 1);
    btnGp2->addButton(ui->radio5, 2);
    connect(btnGp1, SIGNAL(buttonClicked(int)), this, SLOT(on_btnGp1Changed(int)));
    ui->radio1->click();

    //to deal with Responsive Layout
    QGridLayout *responLayout = new QGridLayout(this);
    responLayout->addWidget(ui->gridLayoutWidget);
    //take out title bar and border
    setWindowFlags(Qt::FramelessWindowHint);
}

EntryWidget::~EntryWidget()
{
    delete ui;
    delete btnGp1;
    delete btnGp2;
}

/**
 * @brief EntryWidget::on_btnGp1Changed, slot
 * @param id
 */
void EntryWidget::on_btnGp1Changed(int id)
{
    switch (id) {
    case 1:
        ui->radio4->setEnabled(false);
        ui->radio5->setEnabled(false);
        break;
    case 2:
        ui->radio4->setEnabled(true);
        ui->radio5->setEnabled(true);
        break;
    case 3:
        ui->radio4->setEnabled(true);
        ui->radio5->setEnabled(true);
        break;
    default:
        ui->radio4->setEnabled(false);
        ui->radio5->setEnabled(false);
        break;
    }
}

/**
 * @brief initialze two list, organismSelect and organismSelected.
 */
void EntryWidget::initList()
{
    ui->organismSelect->clear();
    ui->organismSelected->clear();
    OrganismHdl *ogHdl = new OrganismHdl;
    QMap<QString, QString>::iterator it;
    for(it = ogHdl->ogMap.begin(); it != ogHdl->ogMap.end(); it++) {
        ui->organismSelect->addItem(it.key());
    }
    ui->organismSelect->sortItems();
    delete ogHdl;
}

/**
 * @brief validate wheather the input is correct.
 * @return
 */
bool EntryWidget::validateSelected()
{
    int size = ui->organismSelected->count();
    if(size >3) {
        return true;
    }else {
        return false;
    }
}

bool EntryWidget::validateResultType()
{
    if(getResultType() == -1) {
        return false;
    }else {
        return true;
    }
}

bool EntryWidget::validateOption()
{
    if(getResultType() != 1 && getOption() == -1) {
        return false;
    }else {
        return true;
    }
}

/**
 * @brief EntryWidget::getResultType
 * @return
 */
int EntryWidget::getResultType()
{
    return btnGp1->checkedId();
}

/**
 * @brief EntryWidget::getOption
 * @return
 */
int EntryWidget::getOption()
{
    return btnGp2->checkedId();
}

/**
 * @brief call server to execute main function
 */
void EntryWidget::execute()
{
    QList<QString> selectedList;
    int size = ui->organismSelected->count();
    for(int i=0;i<size;i++){
       selectedList.append(ui->organismSelected->item(i)->text());
    }
    if(getResultType() == 1) {
        emit changePage(1);
        emit generateClusterList(selectedList);
    }else if(getResultType() == 2 && getOption() ==1) {
        emit changePage(2);
        emit generateWholePathway(selectedList);
    }else if(getResultType() == 2 && getOption() == 2) {
        emit changePage(2);
        emit generateMetaPathway(selectedList);
    }else if(getResultType() == 3 && getOption() == 1) {
        emit changePage(2);
        emit generateWholeModule(selectedList);
    }else if(getResultType() == 3 && getOption() ==2) {
        emit changePage(2);
        emit generateMetaModule(selectedList);
    }
}

/**
 * @brief EntryWidget::on_organismSelect_doubleClicked, slot
 * @param index
 */
void EntryWidget::on_organismSelect_doubleClicked(const QModelIndex &index)
{
    ui->organismSelected->addItem(ui->organismSelect->currentItem()->text());
    delete ui->organismSelect->takeItem(ui->organismSelect->currentRow());
    ui->organismSelected->sortItems();
}

/**
 * @brief EntryWidget::on_organismSelected_doubleClicked, slot
 * @param index
 */
void EntryWidget::on_organismSelected_doubleClicked(const QModelIndex &index)
{
    ui->organismSelect->addItem(ui->organismSelected->currentItem()->text());
    delete ui->organismSelected->takeItem(ui->organismSelected->currentRow());
    ui->organismSelect->sortItems();
}

/**
 * @brief EntryWidget::on_submit_clicked, slot
 */
void EntryWidget::on_submit_clicked()
{
    if(!validateSelected()) {
        QMessageBox::warning(this, tr("Waring"),tr("Please select at least four oganisms!"),QMessageBox::Yes);
    }else if(!validateResultType()){
        QMessageBox::warning(this, tr("Waring"),tr("Please select a result type!"),QMessageBox::Yes);
    }else if(!validateOption()) {
        QMessageBox::warning(this, tr("Waring"),tr("Please select a option!"),QMessageBox::Yes);
    }else {
        execute();
    }
}
