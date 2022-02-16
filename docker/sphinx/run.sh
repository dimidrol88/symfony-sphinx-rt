#!/usr/bin/env sh

indexer --config /app/etc/sphinx.conf --all
searchd --config /app/etc/sphinx.conf --nodetach