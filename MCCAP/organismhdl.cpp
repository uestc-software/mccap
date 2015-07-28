#include "organismhdl.h"

OrganismHdl::OrganismHdl(QObject *parent) : QObject(parent)
{
    prepareOgMap();
}

OrganismHdl::~OrganismHdl()
{

}

OrganismHdl::prepareOgMap()
{
    ogMap.clear();
    ogMap.insert(QString("aci"), QString("01"));
    ogMap.insert(QString("bsu"), QString("02"));
    ogMap.insert(QString("eco"), QString("03"));
    ogMap.insert(QString("ftn"), QString("04"));
    ogMap.insert(QString("hin"), QString("05"));
    ogMap.insert(QString("hpy"), QString("06"));
    ogMap.insert(QString("mge"), QString("07"));
    ogMap.insert(QString("mpu"), QString("08"));
    ogMap.insert(QString("mtu"), QString("09"));
    ogMap.insert(QString("pau"), QString("10"));
    ogMap.insert(QString("sao"), QString("11"));
    ogMap.insert(QString("spr"), QString("12"));
    ogMap.insert(QString("spu"), QString("13"));
    ogMap.insert(QString("stm"), QString("14"));
    ogMap.insert(QString("stt"), QString("15"));
    ogMap.insert(QString("vch"), QString("16"));
}

