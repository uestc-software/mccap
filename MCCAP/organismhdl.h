#ifndef ORGANISMHDL_H
#define ORGANISMHDL_H

#include <QObject>
#include <QMap>

class OrganismHdl : public QObject
{
    Q_OBJECT
public:
    explicit OrganismHdl(QObject *parent = 0);
    ~OrganismHdl();

    QMap<QString, QString> ogMap;

signals:

public slots:

private:
    init();
    prepareOgMap();
};

#endif // ORGANISMHDL_H
